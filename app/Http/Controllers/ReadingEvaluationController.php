<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use App\Models\ReadingEvaluation;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Auth::user()
            ->readingEvaluations()
            ->with('book')
            ->latest()
            ->paginate(10);

        return view('reading-evaluations.index', compact('evaluations'));
    }

    public function create(Book $book)
    {
        $questions = $book->evaluationQuestions()->orderBy('order')->get();

        if ($questions->isEmpty()) {
            $questions = $this->generateDefaultQuestions($book);
        }

        return view('reading-evaluations.create', compact('book', 'questions'));
    }

    public function store(Request $request, Book $book)
    {
        $questions = $book->evaluationQuestions()->orderBy('order')->get();

        if ($questions->isEmpty()) {
            $questions = $this->generateDefaultQuestions($book);
        }

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'string'],
        ]);

        $recommendation = Auth::user()
            ->recommendations()
            ->where('book_id', $book->id)
            ->latest()
            ->first();

        $evaluation = ReadingEvaluation::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'recommendation_id' => $recommendation?->id,
            'total_questions' => $questions->count(),
        ]);

        $correctCount = 0;
        foreach ($questions as $question) {
            $userAnswer = $validated['answers'][$question->id] ?? '';
            $isCorrect = $this->checkAnswer($question, $userAnswer);

            if ($isCorrect) {
                $correctCount++;
            }

            EvaluationAnswer::create([
                'reading_evaluation_id' => $evaluation->id,
                'evaluation_question_id' => $question->id,
                'answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ]);
        }

        $score = $questions->count() > 0
            ? round(($correctCount / $questions->count()) * 100, 2)
            : 0;
        $passed = $score >= 60;

        $feedback = $this->generateFeedback($score, $passed, $book);

        $evaluation->update([
            'correct_answers' => $correctCount,
            'score' => $score,
            'passed' => $passed,
            'feedback' => $feedback,
        ]);

        if ($passed && $recommendation) {
            $recommendation->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('reading-evaluation.result', $evaluation)
            ->with($passed ? 'success' : 'warning',
                $passed ? '¡Felicidades! Has aprobado la evaluación.' : 'No alcanzaste el puntaje mínimo. ¡Sigue intentando!');
    }

    public function result(ReadingEvaluation $readingEvaluation)
    {
        if ($readingEvaluation->user_id !== Auth::id()) {
            abort(403);
        }

        $readingEvaluation->load('book', 'answers.question');

        return view('reading-evaluations.result', compact('readingEvaluation'));
    }

    private function checkAnswer(EvaluationQuestion $question, string $userAnswer): bool
    {
        $correctAnswer = strtolower(trim($question->correct_answer));
        $userAnswer = strtolower(trim($userAnswer));

        if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
            return $correctAnswer === $userAnswer;
        }

        // For open-ended, check if key words are present
        $keywords = array_filter(explode(' ', $correctAnswer), fn ($w) => mb_strlen($w) > 3);
        $matchCount = 0;
        foreach ($keywords as $keyword) {
            if (str_contains($userAnswer, strtolower($keyword))) {
                $matchCount++;
            }
        }

        return count($keywords) > 0 && ($matchCount / count($keywords)) >= 0.5;
    }

    private function generateFeedback(float $score, bool $passed, Book $book): string
    {
        if ($score >= 90) {
            return "¡Excelente! Demostraste una comprensión profunda de \"{$book->title}\". ¡Sigue así!";
        }
        if ($score >= 70) {
            return "¡Muy bien! Tienes una buena comprensión de \"{$book->title}\". Algunos detalles se te escaparon.";
        }
        if ($score >= 60) {
            return "¡Bien! Aprobaste la evaluación de \"{$book->title}\", pero podrías repasar algunos capítulos.";
        }
        if ($score >= 40) {
            return "Parece que leíste partes de \"{$book->title}\", pero te faltan algunos detalles importantes. Te recomendamos releerlo.";
        }

        return "Parece que aún no has terminado de leer \"{$book->title}\". Te animamos a completar la lectura e intentar de nuevo.";
    }

    private function generateDefaultQuestions(Book $book): \Illuminate\Support\Collection
    {
        $questions = [
            [
                'question' => '¿Cuál es el tema principal de este libro?',
                'type' => 'open_ended',
                'options' => null,
                'correct_answer' => $book->title,
                'explanation' => 'El tema principal se relaciona directamente con el título y contenido del libro.',
                'difficulty' => 1,
                'order' => 1,
            ],
            [
                'question' => '¿Quién es el autor de este libro?',
                'type' => 'multiple_choice',
                'options' => json_encode($this->generateAuthorOptions($book)),
                'correct_answer' => $book->author ?? 'Desconocido',
                'explanation' => 'El autor aparece en la portada y contraportada del libro.',
                'difficulty' => 1,
                'order' => 2,
            ],
            [
                'question' => '¿Recomendarías este libro a un amigo?',
                'type' => 'multiple_choice',
                'options' => json_encode(['Sí, definitivamente', 'Tal vez', 'No estoy seguro', 'No']),
                'correct_answer' => 'Sí, definitivamente',
                'explanation' => 'Compartir libros fomenta la lectura.',
                'difficulty' => 1,
                'order' => 3,
            ],
            [
                'question' => '¿Qué fue lo que más te gustó del libro?',
                'type' => 'open_ended',
                'options' => null,
                'correct_answer' => 'historia personajes trama ilustraciones mensaje enseñanza',
                'explanation' => 'Reflexionar sobre lo que nos gusta ayuda a comprender mejor la lectura.',
                'difficulty' => 2,
                'order' => 4,
            ],
            [
                'question' => '¿Pudiste identificar el mensaje o enseñanza principal del libro?',
                'type' => 'true_false',
                'options' => json_encode(['Verdadero', 'Falso']),
                'correct_answer' => 'Verdadero',
                'explanation' => 'Identificar el mensaje principal demuestra comprensión lectora.',
                'difficulty' => 2,
                'order' => 5,
            ],
        ];

        $createdQuestions = collect();
        foreach ($questions as $q) {
            $createdQuestions->push(
                EvaluationQuestion::create(array_merge($q, ['book_id' => $book->id]))
            );
        }

        return $createdQuestions;
    }

    private function generateAuthorOptions(Book $book): array
    {
        $correct = $book->author ?? 'Desconocido';
        $fakeAuthors = [
            'García Márquez, Gabriel',
            'Cervantes, Miguel de',
            'Neruda, Pablo',
            'Borges, Jorge Luis',
            'Allende, Isabel',
            'Rulfo, Juan',
            'Paz, Octavio',
        ];

        $options = [$correct];
        $shuffled = collect($fakeAuthors)->filter(fn ($a) => $a !== $correct)->shuffle();
        foreach ($shuffled->take(3) as $fake) {
            $options[] = $fake;
        }

        shuffle($options);

        return $options;
    }
}

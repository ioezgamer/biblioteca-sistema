<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\EvaluationQuestion;
use Illuminate\Http\Request;

class EvaluationQuestionController extends Controller
{
    public function index(Book $book)
    {
        $questions = $book->evaluationQuestions()->orderBy('order')->get();

        return view('admin.questions.index', compact('book', 'questions'));
    }

    public function create(Book $book)
    {
        return view('admin.questions.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'type' => ['required', 'in:multiple_choice,true_false,open_ended'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $maxOrder = $book->evaluationQuestions()->max('order') ?? 0;

        $book->evaluationQuestions()->create([
            'question' => $validated['question'],
            'type' => $validated['type'],
            'options' => $validated['type'] !== 'open_ended' ? $validated['options'] : null,
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'],
            'difficulty' => $validated['difficulty'],
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.questions.index', $book)
            ->with('success', 'Pregunta creada exitosamente.');
    }

    public function edit(Book $book, EvaluationQuestion $question)
    {
        return view('admin.questions.edit', compact('book', 'question'));
    }

    public function update(Request $request, Book $book, EvaluationQuestion $question)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'type' => ['required', 'in:multiple_choice,true_false,open_ended'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $question->update([
            'question' => $validated['question'],
            'type' => $validated['type'],
            'options' => $validated['type'] !== 'open_ended' ? $validated['options'] : null,
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'],
            'difficulty' => $validated['difficulty'],
        ]);

        return redirect()->route('admin.questions.index', $book)
            ->with('success', 'Pregunta actualizada exitosamente.');
    }

    public function destroy(Book $book, EvaluationQuestion $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index', $book)
            ->with('success', 'Pregunta eliminada exitosamente.');
    }
}

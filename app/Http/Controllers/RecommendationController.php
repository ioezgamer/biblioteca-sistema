<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');

        $query = $user->recommendations()->with('book.primaryCategory', 'book.categories');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $recommendations = $query->orderByDesc('score')->paginate(12);

        return view('recommendations.index', compact('recommendations', 'status'));
    }

    public function accept(Recommendation $recommendation)
    {
        $this->authorize($recommendation);

        $recommendation->update([
            'status' => 'reading',
            'started_at' => now(),
        ]);

        return back()->with('success', '¡Has comenzado a leer "' . $recommendation->book->title . '"!');
    }

    public function reject(Recommendation $recommendation)
    {
        $this->authorize($recommendation);

        $recommendation->update(['status' => 'rejected']);

        return back()->with('info', 'Recomendación descartada.');
    }

    public function complete(Recommendation $recommendation)
    {
        $this->authorize($recommendation);

        $recommendation->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('reading-evaluation.create', ['book' => $recommendation->book_id])
            ->with('success', '¡Felicidades! Ahora completa la evaluación de lectura.');
    }

    public function refresh(RecommendationService $recommendationService)
    {
        $user = Auth::user();
        $recommendationService->refreshRecommendations($user);

        return redirect()->route('recommendations.index')
            ->with('success', '¡Se han generado nuevas recomendaciones para ti!');
    }

    private function authorize(Recommendation $recommendation): void
    {
        if ($recommendation->user_id !== Auth::id()) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InterestProfile;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitialEvaluationController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if ($user->has_completed_evaluation) {
            return redirect()->route('dashboard');
        }

        $categories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->get();

        return view('evaluation.initial', compact('categories'));
    }

    public function store(Request $request, RecommendationService $recommendationService)
    {
        $validated = $request->validate([
            'reading_level' => ['required', 'in:principiante,intermedio,avanzado,fluido'],
            'age_group' => ['required', 'in:ninos,jovenes,adultos'],
            'preferred_language' => ['required', 'in:es,en,ambos'],
            'categories' => ['required', 'array', 'min:3'],
            'categories.*' => ['exists:categories,id'],
            'reading_goals' => ['nullable', 'string', 'max:500'],
            'books_per_month' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $user = Auth::user();

        $profile = InterestProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'reading_level' => $validated['reading_level'],
                'preferred_language' => $validated['preferred_language'],
                'age_group' => $validated['age_group'],
                'reading_goals' => $validated['reading_goals'],
                'books_per_month' => $validated['books_per_month'],
            ]
        );

        $categoryPreferences = [];
        foreach ($validated['categories'] as $index => $categoryId) {
            $categoryPreferences[$categoryId] = [
                'preference_level' => max(1, 5 - intdiv($index, 3)),
            ];
        }
        $profile->categories()->sync($categoryPreferences);

        $user->update([
            'has_completed_evaluation' => true,
            'reading_level' => $validated['reading_level'],
            'age_group' => $validated['age_group'],
        ]);

        $recommendationService->generateRecommendations($user);

        return redirect()->route('dashboard')
            ->with('success', '¡Evaluación completada! Te hemos preparado recomendaciones personalizadas.');
    }
}

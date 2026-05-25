<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->interestProfile?->load('categories');
        $categories = Category::orderBy('name')->get();

        $stats = [
            'books_completed' => $user->recommendations()->where('status', 'completed')->count(),
            'books_reading' => $user->recommendations()->where('status', 'reading')->count(),
            'evaluations_passed' => $user->readingEvaluations()->where('passed', true)->count(),
            'evaluations_failed' => $user->readingEvaluations()->where('passed', false)->count(),
            'avg_score' => $user->readingEvaluations()->avg('score') ?? 0,
        ];

        return view('profile.show', compact('user', 'profile', 'categories', 'stats'));
    }

    public function update(Request $request, RecommendationService $recommendationService)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'reading_level' => ['required', 'in:principiante,intermedio,avanzado,fluido'],
            'age_group' => ['required', 'in:ninos,jovenes,adultos'],
            'preferred_language' => ['required', 'in:es,en,ambos'],
            'categories' => ['required', 'array', 'min:3'],
            'categories.*' => ['exists:categories,id'],
            'reading_goals' => ['nullable', 'string', 'max:500'],
            'books_per_month' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'reading_level' => $validated['reading_level'],
            'age_group' => $validated['age_group'],
        ]);

        $profile = $user->interestProfile;
        if ($profile) {
            $profile->update([
                'reading_level' => $validated['reading_level'],
                'preferred_language' => $validated['preferred_language'],
                'age_group' => $validated['age_group'],
                'reading_goals' => $validated['reading_goals'],
                'books_per_month' => $validated['books_per_month'],
            ]);

            $categoryPreferences = [];
            foreach ($validated['categories'] as $index => $categoryId) {
                $categoryPreferences[$categoryId] = [
                    'preference_level' => max(1, 5 - intdiv($index, 3)),
                ];
            }
            $profile->categories()->sync($categoryPreferences);
        }

        $recommendationService->refreshRecommendations($user);

        return back()->with('success', 'Perfil actualizado exitosamente. Se han regenerado tus recomendaciones.');
    }
}

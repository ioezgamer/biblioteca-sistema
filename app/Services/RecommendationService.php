<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Recommendation;
use App\Models\User;

class RecommendationService
{
    public function generateRecommendations(User $user, int $limit = 12): void
    {
        $profile = $user->interestProfile()->with('categories')->first();
        if (!$profile) {
            return;
        }

        $existingBookIds = $user->recommendations()->pluck('book_id')->toArray();
        $categoryIds = $profile->categories->pluck('id')->toArray();
        $preferenceMap = $profile->categories->pluck('pivot.preference_level', 'id')->toArray();

        $query = Book::withRealTitle()
            ->available()
            ->whereNotIn('id', $existingBookIds);

        // Filter by language preference
        if ($profile->preferred_language === 'es') {
            $query->where(function ($q) {
                $q->whereHas('categories', function ($sub) {
                    $sub->where('name', 'not like', 'Inglés%');
                })->orWhereDoesntHave('categories');
            });
        } elseif ($profile->preferred_language === 'en') {
            $query->whereHas('categories', function ($q) {
                $q->where('name', 'like', 'Inglés%');
            });
        }

        // Filter by reading level / age group
        $levelCategories = $this->getLevelCategories($profile->reading_level, $profile->age_group);

        $books = $query->with('categories')->get();

        $scoredBooks = [];
        foreach ($books as $book) {
            $score = $this->calculateScore($book, $categoryIds, $preferenceMap, $levelCategories);
            if ($score > 0) {
                $scoredBooks[] = ['book' => $book, 'score' => $score];
            }
        }

        usort($scoredBooks, fn ($a, $b) => $b['score'] <=> $a['score']);

        $topBooks = array_slice($scoredBooks, 0, $limit);

        foreach ($topBooks as $item) {
            $reason = $this->getRecommendationReason($item['book'], $categoryIds);
            Recommendation::create([
                'user_id' => $user->id,
                'book_id' => $item['book']->id,
                'score' => $item['score'],
                'reason' => $reason,
                'status' => 'pending',
            ]);
        }
    }

    public function refreshRecommendations(User $user): void
    {
        $user->recommendations()
            ->where('status', 'pending')
            ->delete();

        $this->generateRecommendations($user);
    }

    private function calculateScore(Book $book, array $categoryIds, array $preferenceMap, array $levelCategories): float
    {
        $score = 0.0;
        $bookCategoryIds = $book->categories->pluck('id')->toArray();
        $bookCategoryNames = $book->categories->pluck('name')->toArray();

        // Category match score (0-50 points)
        foreach ($bookCategoryIds as $catId) {
            if (in_array($catId, $categoryIds)) {
                $score += ($preferenceMap[$catId] ?? 3) * 10;
            }
        }

        // Reading level match (0-30 points)
        foreach ($bookCategoryNames as $catName) {
            if (in_array($catName, $levelCategories)) {
                $score += 30;
                break;
            }
        }

        // Popularity boost (0-10 points)
        $score += min(10, $book->total_circulations * 2);

        // Has author info (5 points)
        if ($book->author) {
            $score += 5;
        }

        // Random factor for variety (0-5 points)
        $score += mt_rand(0, 500) / 100;

        return $score;
    }

    private function getLevelCategories(string $readingLevel, string $ageGroup): array
    {
        $categories = [];

        switch ($readingLevel) {
            case 'principiante':
                $categories = ['Pre-escolar', 'Lectores Emergentes', 'Lectores Principiantes', 'Aprender a Leer', 'Cuentos Ilustrados'];
                break;
            case 'intermedio':
                $categories = ['Lectores Transicionales', 'Lectores Intermedios', 'Cuentos Ilustrados', 'Cómicos'];
                break;
            case 'avanzado':
                $categories = ['Lectores Intermedios', 'Lectores Adolescentes', 'Ficción', 'Novelas', 'Literatura'];
                break;
            case 'fluido':
                $categories = ['Lectores Fluidos', 'Lectores Adolescentes', 'Ficción', 'Novelas', 'Literatura', 'Novelas Gráficas'];
                break;
        }

        switch ($ageGroup) {
            case 'ninos':
                $categories = array_merge($categories, ['Pre-escolar', 'Cuentos Ilustrados', 'Animales']);
                break;
            case 'jovenes':
                $categories = array_merge($categories, ['Lectores Adolescentes', 'Cómicos', 'Novelas Gráficas']);
                break;
            case 'adultos':
                $categories = array_merge($categories, ['Ficción', 'Novelas', 'Literatura', 'Autoayuda', 'Biografía']);
                break;
        }

        return array_unique($categories);
    }

    private function getRecommendationReason(Book $book, array $preferredCategoryIds): string
    {
        $matchingCategories = $book->categories
            ->filter(fn ($cat) => in_array($cat->id, $preferredCategoryIds))
            ->pluck('name')
            ->toArray();

        if (!empty($matchingCategories)) {
            return 'Basado en tu interés en: ' . implode(', ', $matchingCategories);
        }

        if ($book->total_circulations > 3) {
            return 'Libro popular entre otros lectores';
        }

        return 'Recomendado para tu nivel de lectura';
    }
}

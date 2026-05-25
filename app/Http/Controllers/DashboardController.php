<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recommendations = $user->recommendations()
            ->with('book.primaryCategory')
            ->latest()
            ->take(6)
            ->get();

        $recentEvaluations = $user->readingEvaluations()
            ->with('book')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_recommendations' => $user->recommendations()->count(),
            'books_reading' => $user->recommendations()->where('status', 'reading')->count(),
            'books_completed' => $user->recommendations()->where('status', 'completed')->count(),
            'evaluations_passed' => $user->readingEvaluations()->where('passed', true)->count(),
            'evaluations_total' => $user->readingEvaluations()->count(),
        ];

        $totalBooks = Book::withRealTitle()->count();
        $availableBooks = Book::withRealTitle()->available()->count();

        return view('dashboard', compact(
            'recommendations',
            'recentEvaluations',
            'stats',
            'totalBooks',
            'availableBooks'
        ));
    }

    public function welcome()
    {
        $totalBooks = Book::withRealTitle()->count();
        $categories = Category::withCount('books')->orderByDesc('books_count')->take(12)->get();

        return view('welcome', compact('totalBooks', 'categories'));
    }
}

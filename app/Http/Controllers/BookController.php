<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::withRealTitle()->with('primaryCategory', 'categories');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category')) {
            $query->where(function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)
                    ->orWhereHas('categories', function ($sub) use ($categoryId) {
                        $sub->where('categories.id', $categoryId);
                    });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sortField = $request->get('sort', 'title');
        $sortDir = $request->get('dir', 'asc');
        $allowedSorts = ['title', 'author', 'total_circulations', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        $books = $query->paginate(24)->withQueryString();
        $categories = Category::withCount('books')->orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('primaryCategory', 'categories', 'evaluationQuestions');
        $relatedBooks = Book::withRealTitle()
            ->available()
            ->where('id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}

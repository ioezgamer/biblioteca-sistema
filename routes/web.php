<?php

use App\Http\Controllers\Admin\EvaluationQuestionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InitialEvaluationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadingEvaluationController;
use App\Http\Controllers\RecommendationController;
use App\Http\Middleware\EnsureEvaluationCompleted;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [DashboardController::class, 'welcome'])->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Initial evaluation (no evaluation-completed middleware)
    Route::get('/evaluacion-inicial', [InitialEvaluationController::class, 'show'])->name('evaluation.initial');
    Route::post('/evaluacion-inicial', [InitialEvaluationController::class, 'store'])->name('evaluation.initial.store');

    // Routes requiring completed evaluation
    Route::middleware(EnsureEvaluationCompleted::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Books catalog
        Route::get('/catalogo', [BookController::class, 'index'])->name('books.index');
        Route::get('/catalogo/{book}', [BookController::class, 'show'])->name('books.show');

        // Recommendations
        Route::get('/recomendaciones', [RecommendationController::class, 'index'])->name('recommendations.index');
        Route::post('/recomendaciones/{recommendation}/aceptar', [RecommendationController::class, 'accept'])->name('recommendations.accept');
        Route::post('/recomendaciones/{recommendation}/rechazar', [RecommendationController::class, 'reject'])->name('recommendations.reject');
        Route::post('/recomendaciones/{recommendation}/completar', [RecommendationController::class, 'complete'])->name('recommendations.complete');
        Route::post('/recomendaciones/refrescar', [RecommendationController::class, 'refresh'])->name('recommendations.refresh');

        // Reading evaluations
        Route::get('/evaluaciones', [ReadingEvaluationController::class, 'index'])->name('reading-evaluations.index');
        Route::get('/evaluacion/{book}', [ReadingEvaluationController::class, 'create'])->name('reading-evaluation.create');
        Route::post('/evaluacion/{book}', [ReadingEvaluationController::class, 'store'])->name('reading-evaluation.store');
        Route::get('/evaluacion-resultado/{readingEvaluation}', [ReadingEvaluationController::class, 'result'])->name('reading-evaluation.result');

        // Profile
        Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Admin routes
    Route::middleware(IsAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/libro/{book}/preguntas', [EvaluationQuestionController::class, 'index'])->name('questions.index');
        Route::get('/libro/{book}/preguntas/crear', [EvaluationQuestionController::class, 'create'])->name('questions.create');
        Route::post('/libro/{book}/preguntas', [EvaluationQuestionController::class, 'store'])->name('questions.store');
        Route::get('/libro/{book}/preguntas/{question}/editar', [EvaluationQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/libro/{book}/preguntas/{question}', [EvaluationQuestionController::class, 'update'])->name('questions.update');
        Route::delete('/libro/{book}/preguntas/{question}', [EvaluationQuestionController::class, 'destroy'])->name('questions.destroy');
    });
});

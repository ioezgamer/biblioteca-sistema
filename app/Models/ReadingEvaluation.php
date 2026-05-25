<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReadingEvaluation extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'recommendation_id',
        'score', 'total_questions', 'correct_answers',
        'passed', 'feedback',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function recommendation(): BelongsTo
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class);
    }
}

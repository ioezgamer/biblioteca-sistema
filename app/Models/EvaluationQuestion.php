<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationQuestion extends Model
{
    protected $fillable = [
        'book_id', 'question', 'type', 'options',
        'correct_answer', 'explanation', 'difficulty', 'order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class);
    }
}

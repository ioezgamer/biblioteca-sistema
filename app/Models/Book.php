<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'call_number', 'material_type',
        'standard_number', 'lccn', 'barcode', 'status',
        'copy_price', 'currency_code', 'total_circulations',
        'category_id', 'description', 'cover_image',
    ];

    public function primaryCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function evaluationQuestions(): HasMany
    {
        return $this->hasMany(EvaluationQuestion::class);
    }

    public function readingEvaluations(): HasMany
    {
        return $this->hasMany(ReadingEvaluation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    public function scopeWithRealTitle($query)
    {
        return $query->where('title', 'not like', 'Title created by%');
    }
}

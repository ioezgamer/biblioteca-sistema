<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InterestProfile extends Model
{
    protected $fillable = [
        'user_id', 'reading_level', 'preferred_language',
        'age_group', 'favorite_topics', 'reading_goals', 'books_per_month',
    ];

    protected function casts(): array
    {
        return [
            'favorite_topics' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot('preference_level');
    }
}

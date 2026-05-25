<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function interestProfiles(): BelongsToMany
    {
        return $this->belongsToMany(InterestProfile::class, 'interest_profile_category')
            ->withPivot('preference_level');
    }
}

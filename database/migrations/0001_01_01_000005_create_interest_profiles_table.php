<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interest_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reading_level');
            $table->string('preferred_language')->default('es');
            $table->string('age_group');
            $table->json('favorite_topics')->nullable();
            $table->text('reading_goals')->nullable();
            $table->integer('books_per_month')->default(1);
            $table->timestamps();
        });

        Schema::create('interest_profile_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interest_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->integer('preference_level')->default(3);
            $table->unique(['interest_profile_id', 'category_id'], 'ipc_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interest_profile_category');
        Schema::dropIfExists('interest_profiles');
    }
};

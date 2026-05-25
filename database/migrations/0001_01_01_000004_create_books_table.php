<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('call_number')->nullable();
            $table->string('material_type')->default('Book');
            $table->string('standard_number')->nullable();
            $table->string('lccn')->nullable();
            $table->string('barcode')->nullable();
            $table->string('status')->default('Available');
            $table->decimal('copy_price', 10, 2)->nullable();
            $table->string('currency_code')->nullable();
            $table->integer('total_circulations')->default(0);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });

        Schema::create('book_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->unique(['book_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_category');
        Schema::dropIfExists('books');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_series_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description');
            $table->string('image_path');
            $table->string('source_label')->nullable();
            $table->json('ingredients');
            $table->json('steps');
            $table->unsignedSmallInteger('servings')->default(4);
            $table->unsignedSmallInteger('prep_minutes')->default(15);
            $table->unsignedSmallInteger('cook_minutes')->default(30);
            $table->string('difficulty', 20)->default('media');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['anime_series_id', 'published_at']);
            $table->index(['difficulty', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('dish_name');
            $table->string('source_title');
            $table->string('source_type', 20);
            $table->text('reason')->nullable();
            $table->string('email')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index(['source_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_suggestions');
    }
};

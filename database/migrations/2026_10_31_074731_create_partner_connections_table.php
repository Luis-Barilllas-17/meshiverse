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
        Schema::create('partner_connections', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 40)->unique();
            $table->string('service_name')->nullable();
            $table->string('base_url');
            $table->text('access_token');
            $table->json('scopes')->nullable();
            $table->json('capabilities')->nullable();
            $table->string('last_status', 40)->default('pending');
            $table->unsignedInteger('records_count')->default(0);
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_connections');
    }
};

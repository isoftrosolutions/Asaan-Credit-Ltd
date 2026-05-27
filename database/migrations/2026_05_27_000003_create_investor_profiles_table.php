<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('past_investments')->default(0);
            $table->text('portfolio_companies')->nullable();
            $table->decimal('total_capital_deployed', 15, 2)->nullable();
            $table->json('preferred_sectors')->nullable();
            $table->json('preferred_stages')->nullable();
            $table->decimal('ticket_min', 15, 2)->nullable();
            $table->decimal('ticket_max', 15, 2)->nullable();
            $table->json('preferred_geography')->nullable();
            $table->text('references')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_profiles');
    }
};

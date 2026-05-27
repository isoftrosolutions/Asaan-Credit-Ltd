<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pitches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tagline', 140)->nullable();
            $table->text('problem_statement')->nullable();
            $table->text('solution')->nullable();
            $table->text('market_size')->nullable();
            $table->text('business_model')->nullable();
            $table->text('traction')->nullable();
            $table->decimal('funding_amount', 15, 2)->nullable();
            $table->decimal('equity_offered', 5, 2)->nullable();
            $table->text('fund_usage')->nullable();
            $table->decimal('valuation', 15, 2)->nullable();
            $table->string('pitch_deck')->nullable();
            $table->string('pitch_video_url')->nullable();
            $table->string('stage')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pitches');
    }
};

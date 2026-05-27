<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pitches', function (Blueprint $table) {
            $table->string('relocate_willingness')->nullable()->after('runway_months');
            $table->string('financial_projections')->nullable()->after('pitch_deck');
            $table->unsignedTinyInteger('completeness_score')->default(0)->after('is_featured');
            $table->boolean('is_published')->default(false)->after('completeness_score');
        });
    }

    public function down(): void
    {
        Schema::table('pitches', function (Blueprint $table) {
            $table->dropColumn(['relocate_willingness', 'financial_projections', 'completeness_score', 'is_published']);
        });
    }
};

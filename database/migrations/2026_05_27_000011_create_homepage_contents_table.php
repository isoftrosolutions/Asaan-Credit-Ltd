<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('homepage_contents')->insert([
            ['key' => 'banner_headline', 'value' => 'Connect with Investors. Sell or Grow Your Business Faster.'],
            ['key' => 'banner_subtitle', 'value' => 'The premium marketplace where verified business owners meet qualified investors.'],
            ['key' => 'banner_image', 'value' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};

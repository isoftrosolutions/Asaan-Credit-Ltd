<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pitches', function (Blueprint $table) {
            $table->string('company_registration_number')->nullable()->after('tagline');
            $table->string('company_type')->nullable()->after('company_registration_number');
            $table->string('short_summary', 300)->nullable()->after('company_type');
            $table->string('product_stage')->nullable()->after('short_summary');
            $table->text('target_customers')->nullable()->after('traction');
            $table->string('competitors')->nullable()->after('target_customers');
            $table->string('competitive_advantage')->nullable()->after('competitors');
            $table->string('revenue_model')->nullable()->after('business_model');
            $table->decimal('monthly_revenue', 15, 2)->nullable()->after('revenue_model');
            $table->integer('monthly_users')->nullable()->after('monthly_revenue');
            $table->decimal('growth_rate', 5, 2)->nullable()->after('monthly_users');
            $table->decimal('customer_retention', 5, 2)->nullable()->after('growth_rate');
            $table->decimal('minimum_investment', 15, 2)->nullable()->after('funding_amount');
            $table->decimal('previous_funding', 15, 2)->nullable()->after('minimum_investment');
            $table->string('previous_funding_source')->nullable()->after('previous_funding');
            $table->boolean('has_legal_disputes')->default(false)->after('previous_funding_source');
            $table->text('legal_details')->nullable()->after('has_legal_disputes');
            $table->text('existing_debt')->nullable()->after('legal_details');
            $table->string('business_type')->nullable()->after('existing_debt');
            $table->string('customer_type')->nullable()->after('business_type');
            $table->string('looking_for')->nullable()->after('customer_type');
            $table->string('investor_involvement')->nullable()->after('looking_for');
            $table->boolean('open_to_acquisition')->default(false)->after('investor_involvement');
            $table->decimal('monthly_burn', 15, 2)->nullable()->after('open_to_acquisition');
            $table->integer('runway_months')->nullable()->after('monthly_burn');
            $table->json('matchmaking_tags')->nullable()->after('runway_months');
            $table->boolean('is_featured')->default(false)->after('is_hidden');
        });
    }

    public function down(): void
    {
        Schema::table('pitches', function (Blueprint $table) {
            $table->dropColumn([
                'company_registration_number', 'company_type', 'short_summary',
                'product_stage', 'target_customers', 'competitors', 'competitive_advantage',
                'revenue_model', 'monthly_revenue', 'monthly_users', 'growth_rate',
                'customer_retention', 'minimum_investment', 'previous_funding',
                'previous_funding_source', 'has_legal_disputes', 'legal_details',
                'existing_debt', 'business_type', 'customer_type', 'looking_for',
                'investor_involvement', 'open_to_acquisition', 'monthly_burn',
                'runway_months', 'matchmaking_tags', 'is_featured',
            ]);
        });
    }
};

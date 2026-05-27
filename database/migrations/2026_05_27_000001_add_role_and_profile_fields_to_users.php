<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('entrepreneur')->after('email');
            $table->string('account_type')->nullable()->after('role');
            $table->string('phone')->nullable()->after('account_type');
            $table->string('province')->nullable()->after('phone');
            $table->string('district')->nullable()->after('province');
            $table->string('profile_photo')->nullable()->after('district');
            $table->string('company_name')->nullable()->after('profile_photo');
            $table->text('bio')->nullable()->after('company_name');
            $table->string('linkedin_url')->nullable()->after('bio');
            $table->string('website_url')->nullable()->after('linkedin_url');
            $table->string('verification_status')->default('unverified')->after('website_url');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->boolean('is_admin')->default(false)->after('verified_at');
            $table->boolean('is_suspended')->default(false)->after('is_admin');
            $table->integer('daily_request_count')->default(0)->after('is_suspended');
            $table->date('daily_request_date')->nullable()->after('daily_request_count');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'account_type', 'phone', 'province', 'district',
                'profile_photo', 'company_name', 'bio', 'linkedin_url',
                'website_url', 'verification_status', 'verified_at',
                'is_admin', 'is_suspended', 'daily_request_count',
                'daily_request_date', 'deleted_at',
            ]);
        });
    }
};

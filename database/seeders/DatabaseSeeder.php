<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@investmatch.np'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('admin1234'),
                'role' => 'entrepreneur',
                'account_type' => 'individual',
                'verification_status' => 'verified',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Sectors
        $sectors = [
            'AgriTech', 'EdTech', 'FinTech', 'HealthTech', 'CleanTech',
            'E-commerce', 'SaaS', 'Tourism', 'Manufacturing', 'Logistics',
            'Food & Beverage', 'Real Estate', 'Renewable Energy', 'Media & Entertainment',
            'Hospitality', 'Construction', 'Retail', 'Mobility',
        ];
        foreach ($sectors as $name) {
            Sector::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        }

        // FAQs
        $faqs = [
            ['How does the platform ensure profiles are genuine?', 'Every profile is manually reviewed by our analysts. We verify email, phone and identity documents (citizenship for individuals, registration certificate for companies).'],
            ['When do contact details get shared?', 'Contact info is revealed only when both parties express mutual interest. This prevents unsolicited outreach and protects identity until you are ready to connect.'],
            ['What types of opportunities are supported?', 'Equity investment, partial stake sale, business loan, franchise expansion and strategic partnership opportunities across all industries.'],
            ['Is there a fee to use InvestMatch?', 'Registration and basic usage is free in v1. Monetisation features are reserved for v2.'],
            ['How long does verification take?', 'Admin reviews verification documents within 48 hours. You will get an in-app notification and email when reviewed.'],
            ['Can I send unlimited interest requests?', 'Verified investors can send up to 10 interest requests per day. Entrepreneurs can receive unlimited requests.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(
                ['question' => $q],
                ['answer' => $a, 'sort_order' => $i, 'is_active' => true]
            );
        }

        // Demo investor
        $investor = User::updateOrCreate(
            ['email' => 'investor@demo.np'],
            [
                'name' => 'Ramesh Thapa',
                'password' => Hash::make('demo1234'),
                'role' => 'investor',
                'account_type' => 'individual',
                'phone' => '9851000001',
                'province' => 'Bagmati',
                'district' => 'Kathmandu',
                'bio' => 'Angel investor focused on early-stage Nepali tech. 15 years in banking & PE.',
                'linkedin_url' => 'https://linkedin.com/in/demo',
                'verification_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );
        $investor->investorProfile()->updateOrCreate(
            ['user_id' => $investor->id],
            [
                'past_investments' => 12,
                'portfolio_companies' => 'eSewa, Foodmandu, Tootle, Khalti',
                'total_capital_deployed' => 25000000,
                'preferred_sectors' => ['AgriTech', 'FinTech', 'HealthTech'],
                'preferred_stages' => ['mvp', 'early_revenue'],
                'ticket_min' => 1500000,
                'ticket_max' => 20000000,
                'preferred_geography' => ['Bagmati', 'Gandaki'],
                'references' => 'Available on request.',
            ]
        );

        // Demo entrepreneur
        $entrepreneur = User::updateOrCreate(
            ['email' => 'founder@demo.np'],
            [
                'name' => 'Anita Gurung',
                'password' => Hash::make('demo1234'),
                'role' => 'entrepreneur',
                'account_type' => 'company',
                'company_name' => 'KrishiNow Pvt. Ltd.',
                'phone' => '9841000001',
                'province' => 'Gandaki',
                'district' => 'Pokhara',
                'bio' => 'Helping smallholder farmers in mid-hills sell produce online.',
                'verification_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        // Demo pitch
        if ($entrepreneur && !$entrepreneur->pitches()->exists()) {
            $sector = Sector::where('slug', 'agritech')->first();
            $entrepreneur->pitches()->create([
                'tagline' => 'Direct-to-consumer marketplace for mid-hills farmers in Nepal.',
                'short_summary' => 'Connecting 3,000+ farmers to urban buyers via mobile app and pickup points.',
                'problem_statement' => 'Smallholder farmers lose 30-40% of margins to middlemen. Urban buyers pay premium for low-quality produce.',
                'solution' => 'Mobile-first marketplace with cold chain logistics partnership and quality verification at source.',
                'market_size' => 'NPR 12 Cr addressable agri-commerce market growing 18% YoY.',
                'business_model' => '10% commission on transactions + premium subscription for buyers.',
                'traction' => '3,000 farmers onboarded, NPR 8L monthly GMV, 22% MoM growth.',
                'funding_amount' => 5000000,
                'equity_offered' => 12.5,
                'fund_usage' => 'Product (40%), Hiring (30%), Marketing (20%), Operations (10%).',
                'valuation' => 40000000,
                'pitch_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'stage' => 'early_revenue',
                'product_stage' => 'revenue_generating',
                'sector_id' => $sector?->id,
                'business_type' => 'tech',
                'customer_type' => 'b2c',
                'looking_for' => 'angel_investor',
                'is_active' => true,
                'is_hidden' => false,
                'is_featured' => true,
            ]);
        }
    }
}

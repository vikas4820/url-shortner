<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShortUrlSeeder extends Seeder
{
    public function run(): void
    {
        $acmeCompany = Company::where('slug', 'acme')->firstOrFail();
        $globalCompany = Company::where('slug', 'global-tech')->firstOrFail();
        $startupCompany = Company::where('slug', 'startup-xyz')->firstOrFail();

        $salesAcme = User::where('email', 'sales@acme.com')->firstOrFail();
        $managerAcme = User::where('email', 'manager@acme.com')->firstOrFail();
        $memberAcme = User::where('email', 'member@acme.com')->firstOrFail();
        $salesGlobal = User::where('email', 'sales@global.com')->firstOrFail();
        $adminStartup = User::where('email', 'admin@startup.com')->firstOrFail();

        $shortUrls = [
            // Acme URLs
            [
                'company_id' => $acmeCompany->id,
                'user_id' => $salesAcme->id,
                'original_url' => 'https://acme.com/products/widget-pro',
                'short_code' => 'widget1',
                'clicks' => 42,
                'expires_at' => now()->addDays(30),
            ],
            [
                'company_id' => $acmeCompany->id,
                'user_id' => $salesAcme->id,
                'original_url' => 'https://acme.com/blog/q4-roadmap',
                'short_code' => 'roadmap',
                'clicks' => 18,
                'expires_at' => now()->addDays(14),
            ],
            [
                'company_id' => $acmeCompany->id,
                'user_id' => $managerAcme->id,
                'original_url' => 'https://acme.com/team/dashboard',
                'short_code' => 'teamdash',
                'clicks' => 156,
                'expires_at' => null,
            ],
            [
                'company_id' => $acmeCompany->id,
                'user_id' => $memberAcme->id,
                'original_url' => 'https://acme.com/docs/api-reference',
                'short_code' => 'apiref',
                'clicks' => 7,
                'expires_at' => now()->addDays(60),
            ],
            // Expired URL
            [
                'company_id' => $acmeCompany->id,
                'user_id' => $salesAcme->id,
                'original_url' => 'https://acme.com/promo/black-friday',
                'short_code' => 'bf2024',
                'clicks' => 234,
                'expires_at' => now()->subDays(2),
            ],

            // Global Tech URLs
            [
                'company_id' => $globalCompany->id,
                'user_id' => $salesGlobal->id,
                'original_url' => 'https://globaltech.io/enterprise/solutions',
                'short_code' => 'ent-sol',
                'clicks' => 89,
                'expires_at' => now()->addDays(90),
            ],
            [
                'company_id' => $globalCompany->id,
                'user_id' => $salesGlobal->id,
                'original_url' => 'https://globaltech.io/careers/engineering',
                'short_code' => 'careers',
                'clicks' => 31,
                'expires_at' => null,
            ],

            // Startup URLs
            [
                'company_id' => $startupCompany->id,
                'user_id' => $adminStartup->id,
                'original_url' => 'https://startupxyz.com/launch',
                'short_code' => 'launch',
                'clicks' => 523,
                'expires_at' => now()->addDays(7),
            ],
        ];

        foreach ($shortUrls as $shortUrl) {
            ShortUrl::firstOrCreate(
                ['short_code' => $shortUrl['short_code']],
                $shortUrl
            );
        }

        $this->command->info('Short URLs seeded: ' . count($shortUrls));
    }
}
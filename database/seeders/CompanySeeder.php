<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'System Company', 'slug' => 'system'],
            ['name' => 'Acme Corporation', 'slug' => 'acme'],
            ['name' => 'Global Tech Inc', 'slug' => 'global-tech'],
            ['name' => 'StartupXYZ', 'slug' => 'startup-xyz'],
        ];

        foreach ($companies as $company) {
            Company::firstOrCreate(
                ['slug' => $company['slug']],
                ['name' => $company['name']]
            );
        }

        $this->command->info('Companies seeded: ' . count($companies));
    }
}
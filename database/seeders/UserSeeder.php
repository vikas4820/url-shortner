<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $systemCompany = Company::where('slug', 'system')->firstOrFail();
        $acmeCompany = Company::where('slug', 'acme')->firstOrFail();
        $globalCompany = Company::where('slug', 'global-tech')->firstOrFail();
        $startupCompany = Company::where('slug', 'startup-xyz')->firstOrFail();

        // Super Admin (System Company) - Created via raw SQL as required
        DB::insert('INSERT INTO users (name, email, password, company_id, email_verified_at, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)', [
            'Super Admin',
            'superadmin@example.com',
            Hash::make('SuperAdmin123!'),
            $systemCompany->id,
            now(),
            now(),
            now(),
        ]);
        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $superAdmin->assignRole('super_admin');

        // Admin (Acme)
        $admin = User::firstOrCreate(
            ['email' => 'admin@acme.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'company_id' => $acmeCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Manager (Acme)
        $manager = User::firstOrCreate(
            ['email' => 'manager@acme.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'company_id' => $acmeCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('manager');

        // Sales (Acme)
        $sales = User::firstOrCreate(
            ['email' => 'sales@acme.com'],
            [
                'name' => 'Sales User',
                'password' => Hash::make('password'),
                'company_id' => $acmeCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $sales->assignRole('sales');

        // Member (Acme)
        $member = User::firstOrCreate(
            ['email' => 'member@acme.com'],
            [
                'name' => 'Member User',
                'password' => Hash::make('password'),
                'company_id' => $acmeCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $member->assignRole('member');

        // Admin (Global Tech)
        $globalAdmin = User::firstOrCreate(
            ['email' => 'admin@global.com'],
            [
                'name' => 'Global Admin',
                'password' => Hash::make('password'),
                'company_id' => $globalCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $globalAdmin->assignRole('admin');

        // Sales (Global Tech)
        $globalSales = User::firstOrCreate(
            ['email' => 'sales@global.com'],
            [
                'name' => 'Global Sales',
                'password' => Hash::make('password'),
                'company_id' => $globalCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $globalSales->assignRole('sales');

        // Admin (Startup)
        $startupAdmin = User::firstOrCreate(
            ['email' => 'admin@startup.com'],
            [
                'name' => 'Startup Admin',
                'password' => Hash::make('password'),
                'company_id' => $startupCompany->id,
                'email_verified_at' => now(),
            ]
        );
        $startupAdmin->assignRole('admin');

        $this->command->info('Users seeded with roles across companies');
    }
}
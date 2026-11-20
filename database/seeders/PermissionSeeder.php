<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_companies',
            'manage_users',
            'invite_super_admin',
            'invite_admin',
            'invite_manager',
            'invite_sales',
            'invite_member',
            'create_short_url',
            'view_all_short_urls',
            'view_short_url_details',
            'access_short_url_redirect',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('Permissions seeded: ' . count($permissions));
    }
}
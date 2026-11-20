<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);
        $superAdmin->syncPermissions([
            'manage_companies',
            'manage_users',
            'invite_super_admin',
            'invite_admin',
            'invite_manager',
            'invite_sales',
            'invite_member',
            'view_short_url_details',
            'access_short_url_redirect',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $admin->syncPermissions([
            'manage_users',
            'invite_sales',
            'invite_member',
            'view_all_short_urls',
            'view_short_url_details',
            'access_short_url_redirect',
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'web',
        ]);
        $manager->syncPermissions([
            'view_all_short_urls',
            'view_short_url_details',
            'access_short_url_redirect',
        ]);

        $sales = Role::firstOrCreate([
            'name' => 'sales',
            'guard_name' => 'web',
        ]);
        $sales->syncPermissions([
            'create_short_url',
            'view_short_url_details',
            'access_short_url_redirect',
        ]);

        $member = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => 'web',
        ]);
        $member->syncPermissions([
            'view_short_url_details',
            'access_short_url_redirect',
        ]);

        $this->command->info('Roles seeded with permissions');
    }
}
<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('roles');

test('super_admin has correct permissions', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    expect($superAdmin->hasRole('super_admin'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('manage_companies'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('manage_users'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('invite_admin'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('invite_manager'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('invite_sales'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('invite_member'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('view_short_url_details'))->toBeTrue();
    expect($superAdmin->hasPermissionTo('access_short_url_redirect'))->toBeTrue();
    // SuperAdmin should NOT have these
    expect($superAdmin->hasPermissionTo('create_short_url'))->toBeFalse();
    expect($superAdmin->hasPermissionTo('view_all_short_urls'))->toBeFalse();
});

test('admin has correct permissions', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    expect($admin->hasRole('admin'))->toBeTrue();
    expect($admin->hasPermissionTo('manage_users'))->toBeTrue();
    expect($admin->hasPermissionTo('invite_sales'))->toBeTrue();
    expect($admin->hasPermissionTo('invite_member'))->toBeTrue();
    expect($admin->hasPermissionTo('view_all_short_urls'))->toBeTrue();
    expect($admin->hasPermissionTo('view_short_url_details'))->toBeTrue();
    expect($admin->hasPermissionTo('access_short_url_redirect'))->toBeTrue();
    // Admin should NOT have these
    expect($admin->hasPermissionTo('create_short_url'))->toBeFalse();
    expect($admin->hasPermissionTo('invite_admin'))->toBeFalse();
    expect($admin->hasPermissionTo('invite_manager'))->toBeFalse();
});

test('manager has correct permissions', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-2'], ['name' => 'Test Company 2']);
    $manager = User::create([
        'name' => 'Manager',
        'email' => 'manager@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $manager->assignRole('manager');

    expect($manager->hasRole('manager'))->toBeTrue();
    expect($manager->hasPermissionTo('view_all_short_urls'))->toBeTrue();
    expect($manager->hasPermissionTo('view_short_url_details'))->toBeTrue();
    expect($manager->hasPermissionTo('access_short_url_redirect'))->toBeTrue();
    // Manager should NOT have these
    expect($manager->hasPermissionTo('create_short_url'))->toBeFalse();
    expect($manager->hasPermissionTo('invite_sales'))->toBeFalse();
    expect($manager->hasPermissionTo('invite_member'))->toBeFalse();
});

test('sales has correct permissions', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-3'], ['name' => 'Test Company 3']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    expect($sales->hasRole('sales'))->toBeTrue();
    expect($sales->hasPermissionTo('create_short_url'))->toBeTrue();
    expect($sales->hasPermissionTo('view_short_url_details'))->toBeTrue();
    expect($sales->hasPermissionTo('access_short_url_redirect'))->toBeTrue();
    // Sales should NOT have these
    expect($sales->hasPermissionTo('view_all_short_urls'))->toBeFalse();
    expect($sales->hasPermissionTo('invite_sales'))->toBeFalse();
    expect($sales->hasPermissionTo('invite_member'))->toBeFalse();
});

test('member has correct permissions', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-4'], ['name' => 'Test Company 4']);
    $member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $member->assignRole('member');

    expect($member->hasRole('member'))->toBeTrue();
    expect($member->hasPermissionTo('view_short_url_details'))->toBeTrue();
    expect($member->hasPermissionTo('access_short_url_redirect'))->toBeTrue();
    // Member should NOT have these
    expect($member->hasPermissionTo('create_short_url'))->toBeFalse();
    expect($member->hasPermissionTo('view_all_short_urls'))->toBeFalse();
    expect($member->hasPermissionTo('invite_sales'))->toBeFalse();
    expect($member->hasPermissionTo('invite_member'))->toBeFalse();
});

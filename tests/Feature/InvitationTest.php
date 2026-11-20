<?php

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('invitations');

test('super_admin cannot invite admin', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'newadmin@test.com',
        'role' => 'admin',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('super_admin can invite manager', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'newmanager@test.com',
        'role' => 'manager',
    ]);

    $response->assertRedirect(route('invitations.index'));
    $invitation = Invitation::where('email', 'newmanager@test.com')->first();
    expect($invitation->role)->toBe('manager');
});

test('super_admin can invite sales', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'newsales@test.com',
        'role' => 'sales',
    ]);

    $response->assertRedirect(route('invitations.index'));
    $invitation = Invitation::where('email', 'newsales@test.com')->first();
    expect($invitation->role)->toBe('sales');
});

test('super_admin can invite member', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'newmember@test.com',
        'role' => 'member',
    ]);

    $response->assertRedirect(route('invitations.index'));
    $invitation = Invitation::where('email', 'newmember@test.com')->first();
    expect($invitation->role)->toBe('member');
});

test('admin cannot invite admin', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('invitations.store'), [
        'email' => 'newadmin@test.com',
        'role' => 'admin',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('admin cannot invite manager', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('invitations.store'), [
        'email' => 'newmanager@test.com',
        'role' => 'manager',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('admin can invite sales', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('invitations.store'), [
        'email' => 'newsales@test.com',
        'role' => 'sales',
    ]);

    $response->assertRedirect(route('invitations.index'));
    $invitation = Invitation::where('email', 'newsales@test.com')->first();
    expect($invitation->role)->toBe('sales');
    expect($invitation->company_id)->toBe($company->id);
});

test('admin cannot invite member into own company', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('invitations.store'), [
        'email' => 'newmember@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('manager cannot invite anyone', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-2'], ['name' => 'Test Company 2']);
    $manager = User::create([
        'name' => 'Manager',
        'email' => 'manager@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $manager->assignRole('manager');

    $response = $this->actingAs($manager)->post(route('invitations.store'), [
        'email' => 'newmember@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(403);
});

test('sales cannot invite anyone', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-3'], ['name' => 'Test Company 3']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $response = $this->actingAs($sales)->post(route('invitations.store'), [
        'email' => 'newmember@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(403);
});

test('member cannot invite anyone', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-4'], ['name' => 'Test Company 4']);
    $member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $member->assignRole('member');

    $response = $this->actingAs($member)->post(route('invitations.store'), [
        'email' => 'newmember@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(403);
});

test('invitation token is unique and secure', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'token-test@test.com',
        'role' => 'member',
    ]);

    $invitation = Invitation::where('email', 'token-test@test.com')->first();
    expect($invitation->token)->toHaveLength(64);
    expect(Invitation::where('token', $invitation->token)->count())->toBe(1);
});

test('invitation expires after 24 hours', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'expire-test@test.com',
        'role' => 'member',
    ]);

    $invitation = Invitation::where('email', 'expire-test@test.com')->first();
    $diffInHours = $invitation->expires_at->diffInHours(now(), true);
    expect($diffInHours)->toBeGreaterThanOrEqual(23);
    expect($diffInHours)->toBeLessThanOrEqual(24);
});

test('expired invitation cannot be accepted', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $invitation = Invitation::create([
        'company_id' => $company->id,
        'email' => 'expired@test.com',
        'role' => 'member',
        'token' => 'expired-token-123',
        'expires_at' => now()->subHours(1),
        'invited_by_id' => $superAdmin->id,
    ]);

    $response = $this->get(route('invitations.accept', ['token' => $invitation->token]));
    $response->assertStatus(200);
    $response->assertViewIs('invitations.expired');
});

test('accepted invitation cannot be reused', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $invitation = Invitation::create([
        'company_id' => $company->id,
        'email' => 'accepted@test.com',
        'role' => 'member',
        'token' => 'accepted-token-123',
        'expires_at' => now()->addHours(24),
        'invited_by_id' => $superAdmin->id,
        'accepted_at' => now(),
    ]);

    $response = $this->get(route('invitations.accept', ['token' => $invitation->token]));
    $response->assertStatus(200);
    $response->assertViewIs('invitations.accepted');
});

test('invalid invitation token returns 404', function () {
    $response = $this->get(route('invitations.accept', ['token' => 'invalid-token']));
    $response->assertStatus(404);
});

test('duplicate invitation for same email is prevented', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'duplicate@test.com',
        'role' => 'member',
    ]);

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'duplicate@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
});

test('invitation for existing user is prevented', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    User::create([
        'name' => 'Existing User',
        'email' => 'existing@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($superAdmin)->post(route('invitations.store'), [
        'email' => 'existing@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
});

test('accepted invitation creates user with correct role and company', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $invitation = Invitation::create([
        'company_id' => $company->id,
        'email' => 'newuser@test.com',
        'role' => 'sales',
        'token' => 'accept-test-token',
        'expires_at' => now()->addHours(24),
        'invited_by_id' => $superAdmin->id,
    ]);

    $response = $this->post(route('invitations.confirm.accept', ['token' => $invitation->token]), [
        'name' => 'New User',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $user = User::where('email', 'newuser@test.com')->first();
    expect($user)->not->toBeNull();
    expect($user->company_id)->toBe($company->id);
    expect($user->hasRole('sales'))->toBeTrue();
    expect($invitation->fresh()->accepted_at)->not->toBeNull();
});

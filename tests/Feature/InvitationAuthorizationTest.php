<?php

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('invitation-authorization');

beforeEach(function () {
    $this->company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $this->otherCompany = Company::firstOrCreate(['slug' => 'other-company'], ['name' => 'Other Company']);

    $this->admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $this->company->id,
        'email_verified_at' => now(),
    ]);
    $this->admin->assignRole('admin');

    $this->manager = User::create([
        'name' => 'Manager',
        'email' => 'manager@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $this->company->id,
        'email_verified_at' => now(),
    ]);
    $this->manager->assignRole('manager');

    $this->sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $this->company->id,
        'email_verified_at' => now(),
    ]);
    $this->sales->assignRole('sales');

    $this->member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $this->company->id,
        'email_verified_at' => now(),
    ]);
    $this->member->assignRole('member');
});

test('admin can access invitations index', function () {
    $response = $this->actingAs($this->admin)->get(route('invitations.index'));
    $response->assertStatus(200);
});

test('manager cannot access invitations index', function () {
    $response = $this->actingAs($this->manager)->get(route('invitations.index'));
    $response->assertStatus(403);
});

test('sales cannot access invitations index', function () {
    $response = $this->actingAs($this->sales)->get(route('invitations.index'));
    $response->assertStatus(403);
});

test('member cannot access invitations index', function () {
    $response = $this->actingAs($this->member)->get(route('invitations.index'));
    $response->assertStatus(403);
});

test('admin can access invitations create', function () {
    $response = $this->actingAs($this->admin)->get(route('invitations.create'));
    $response->assertStatus(200);
});

test('manager cannot access invitations create', function () {
    $response = $this->actingAs($this->manager)->get(route('invitations.create'));
    $response->assertStatus(403);
});

test('sales cannot access invitations create', function () {
    $response = $this->actingAs($this->sales)->get(route('invitations.create'));
    $response->assertStatus(403);
});

test('member cannot access invitations create', function () {
    $response = $this->actingAs($this->member)->get(route('invitations.create'));
    $response->assertStatus(403);
});

test('admin can create invitation', function () {
    $response = $this->actingAs($this->admin)->post(route('invitations.store'), [
        'email' => 'newuser@test.com',
        'role' => 'sales',
    ]);

    $response->assertRedirect(route('invitations.index'));
    $this->assertDatabaseHas('invitations', [
        'email' => 'newuser@test.com',
        'company_id' => $this->company->id,
    ]);
});

test('admin cannot create invitation with invalid role', function () {
    $response = $this->actingAs($this->admin)->post(route('invitations.store'), [
        'email' => 'newuser@test.com',
        'role' => 'admin',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('admin can view invitation in same company', function () {
    $invitation = Invitation::create([
        'company_id' => $this->company->id,
        'email' => 'invited@test.com',
        'role' => 'member',
        'token' => 'test-token-123',
        'expires_at' => now()->addHours(24),
        'invited_by_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->admin)->get(route('invitations.show', $invitation));
    $response->assertStatus(200);
});

test('admin cannot view invitation in other company', function () {
    $invitation = Invitation::create([
        'company_id' => $this->otherCompany->id,
        'email' => 'invited@test.com',
        'role' => 'member',
        'token' => 'test-token-123',
        'expires_at' => now()->addHours(24),
        'invited_by_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->admin)->get(route('invitations.show', $invitation));
    $response->assertStatus(403);
});

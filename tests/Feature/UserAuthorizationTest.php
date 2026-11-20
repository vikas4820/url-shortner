<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('user-authorization');

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

    $this->otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $this->otherCompany->id,
        'email_verified_at' => now(),
    ]);
    $this->otherUser->assignRole('member');
});

test('admin can access users index', function () {
    $response = $this->actingAs($this->admin)->get(route('users.index'));
    $response->assertStatus(200);
});

test('manager cannot access users index', function () {
    $response = $this->actingAs($this->manager)->get(route('users.index'));
    $response->assertStatus(403);
});

test('sales cannot access users index', function () {
    $response = $this->actingAs($this->sales)->get(route('users.index'));
    $response->assertStatus(403);
});

test('member cannot access users index', function () {
    $response = $this->actingAs($this->member)->get(route('users.index'));
    $response->assertStatus(403);
});

test('admin can access users create', function () {
    $response = $this->actingAs($this->admin)->get(route('users.create'));
    $response->assertStatus(200);
});

test('manager cannot access users create', function () {
    $response = $this->actingAs($this->manager)->get(route('users.create'));
    $response->assertStatus(403);
});

test('admin can create user in same company', function () {
    $response = $this->actingAs($this->admin)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'newuser@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'sales',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'newuser@test.com',
        'company_id' => $this->company->id,
    ]);
});

test('admin cannot create user with invalid role', function () {
    $response = $this->actingAs($this->admin)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'newuser@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('role');
});

test('admin can view user in same company', function () {
    $response = $this->actingAs($this->admin)->get(route('users.show', $this->sales));
    $response->assertStatus(200);
});

test('admin cannot view user in other company', function () {
    $response = $this->actingAs($this->admin)->get(route('users.show', $this->otherUser));
    $response->assertStatus(403);
});

test('admin can edit user in same company', function () {
    $response = $this->actingAs($this->admin)->get(route('users.edit', $this->sales));
    $response->assertStatus(200);
});

test('admin cannot edit user in other company', function () {
    $response = $this->actingAs($this->admin)->get(route('users.edit', $this->otherUser));
    $response->assertStatus(403);
});

test('admin can update user in same company', function () {
    $response = $this->actingAs($this->admin)->put(route('users.update', $this->sales), [
        'name' => 'Updated Sales',
        'email' => 'sales@test.com',
        'role' => 'sales',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->sales->refresh();
    expect($this->sales->name)->toBe('Updated Sales');
});

test('admin cannot update user in other company', function () {
    $response = $this->actingAs($this->admin)->put(route('users.update', $this->otherUser), [
        'name' => 'Updated Other',
        'email' => 'other@test.com',
        'role' => 'member',
    ]);

    $response->assertStatus(403);
});

test('admin cannot delete self', function () {
    $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));
    $response->assertRedirect();
    $response->assertSessionHasErrors('user');
    $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
});

test('admin can delete user in same company', function () {
    $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->sales));
    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $this->sales->id]);
});

test('admin cannot delete user in other company', function () {
    $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->otherUser));
    $response->assertStatus(403);
});

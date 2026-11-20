<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('authentication');

test('login page is accessible to guests', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
    $response->assertSee('Sign in');
});

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $response = $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $response = $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('user cannot login with non-existent email', function () {
    $response = $this->post(route('login'), [
        'email' => 'nonexistent@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('authenticated user cannot access login page', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $response = $this->actingAs($user)->get(route('login'));
    $response->assertRedirect(route('dashboard'));
});

test('user can logout', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('guest cannot access dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
});

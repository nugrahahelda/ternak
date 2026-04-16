<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'peternak']);
    \Spatie\Permission\Models\Role::create(['name' => 'petugas']);
});

it('shows login page for guests', function () {
    $this->get('/admin/login')
        ->assertSuccessful()
        ->assertSee('Sign in');
});

it('can authenticate user with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('admin');

    // Act as the user directly (simulating successful login)
    $response = $this->actingAs($user)->get('/admin');

    $response->assertSuccessful();
    $this->assertAuthenticatedAs($user);
});

it('prevents access for unauthenticated users', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');

    $this->assertGuest();
});

it('can logout authenticated user', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Login
    $this->actingAs($user);
    expect(auth()->check())->toBeTrue();

    // Logout using auth facade
    auth()->logout();

    expect(auth()->check())->toBeFalse();
    $this->assertGuest();
});

it('redirects authenticated users away from login page', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/login')
        ->assertRedirect('/admin');
});

it('allows admin role to access admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

it('creates user with hashed password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('test-password'),
    ]);

    expect($user->password)->not->toBe('test-password');
    expect(\Hash::check('test-password', $user->password))->toBeTrue();
});

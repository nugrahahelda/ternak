<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'peternak']);
    \Spatie\Permission\Models\Role::create(['name' => 'petugas']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('redirects unauthenticated users to login', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

it('allows authenticated admin to access dashboard', function () {
    $this->actingAs($this->admin)
        ->get('/admin')
        ->assertSuccessful();
});

it('allows admin to access animals page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/animals')
        ->assertSuccessful();
});

it('allows admin to access animals create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/animals/create')
        ->assertSuccessful();
});

it('allows admin to access cages page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/cages')
        ->assertSuccessful();
});

it('allows admin to access cages create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/cages/create')
        ->assertSuccessful();
});

it('allows admin to access egg productions page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/egg-productions')
        ->assertSuccessful();
});

it('allows admin to access egg productions create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/egg-productions/create')
        ->assertSuccessful();
});

it('allows admin to access farmers page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/farmers')
        ->assertSuccessful();
});

it('allows admin to access farmers create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/farmers/create')
        ->assertSuccessful();
});

it('allows admin to access feed records page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/feed-records')
        ->assertSuccessful();
});

it('allows admin to access feed records create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/feed-records/create')
        ->assertSuccessful();
});

it('allows admin to access health records page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/health-records')
        ->assertSuccessful();
});

it('allows admin to access health records create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/health-records/create')
        ->assertSuccessful();
});

it('allows admin to access users page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/users')
        ->assertSuccessful();
});

it('allows admin to access users create page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/users/create')
        ->assertSuccessful();
});

it('allows admin to access egg production report', function () {
    $this->actingAs($this->admin)
        ->get('/admin/egg-production-report')
        ->assertSuccessful();
});

it('allows admin to access feed report', function () {
    $this->actingAs($this->admin)
        ->get('/admin/feed-report')
        ->assertSuccessful();
});

it('allows admin to access health report', function () {
    $this->actingAs($this->admin)
        ->get('/admin/health-report')
        ->assertSuccessful();
});

it('allows admin to access animals edit page', function () {
    $farmer = \App\Models\Farmer::factory()->create();
    $animal = \App\Models\Animal::factory()->create(['farmer_id' => $farmer->id]);

    $this->actingAs($this->admin)
        ->get("/admin/animals/{$animal->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access cages edit page', function () {
    $cage = \App\Models\Cage::factory()->create();

    $this->actingAs($this->admin)
        ->get("/admin/cages/{$cage->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access egg productions edit page', function () {
    $farmer = \App\Models\Farmer::factory()->create();
    $animal = \App\Models\Animal::factory()->create(['farmer_id' => $farmer->id]);
    $eggProduction = \App\Models\EggProduction::factory()->create(['animal_id' => $animal->id]);

    $this->actingAs($this->admin)
        ->get("/admin/egg-productions/{$eggProduction->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access farmers edit page', function () {
    $farmer = \App\Models\Farmer::factory()->create();

    $this->actingAs($this->admin)
        ->get("/admin/farmers/{$farmer->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access feed records edit page', function () {
    $farmer = \App\Models\Farmer::factory()->create();
    $animal = \App\Models\Animal::factory()->create(['farmer_id' => $farmer->id]);
    $feedRecord = \App\Models\FeedRecord::factory()->create(['animal_id' => $animal->id]);

    $this->actingAs($this->admin)
        ->get("/admin/feed-records/{$feedRecord->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access health records edit page', function () {
    $farmer = \App\Models\Farmer::factory()->create();
    $animal = \App\Models\Animal::factory()->create(['farmer_id' => $farmer->id]);
    $healthRecord = \App\Models\HealthRecord::factory()->create(['animal_id' => $animal->id]);

    $this->actingAs($this->admin)
        ->get("/admin/health-records/{$healthRecord->id}/edit")
        ->assertSuccessful();
});

it('allows admin to access users edit page', function () {
    $user = \App\Models\User::factory()->create();
    $user->assignRole('peternak');

    $this->actingAs($this->admin)
        ->get("/admin/users/{$user->id}/edit")
        ->assertSuccessful();
});

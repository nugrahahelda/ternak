<?php

use App\Models\Animal;
use App\Models\Cage;
use App\Models\EggProduction;
use App\Models\Farmer;
use App\Models\FeedRecord;
use App\Models\HealthRecord;
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
    $this->actingAs($this->admin);
});

it('can create a farmer', function () {
    $farmer = Farmer::factory()->make();

    expect(Farmer::count())->toBe(0);

    Farmer::create($farmer->toArray());

    expect(Farmer::count())->toBe(1);
});

it('can create a cage', function () {
    $cage = Cage::factory()->make();

    expect(Cage::count())->toBe(0);

    Cage::create($cage->toArray());

    expect(Cage::count())->toBe(1);
});

it('can create an animal', function () {
    $farmer = Farmer::factory()->create();
    $cage = Cage::factory()->create();

    $animal = Animal::factory()->make([
        'farmer_id' => $farmer->id,
        'cage_id' => $cage->id,
    ]);

    expect(Animal::count())->toBe(0);

    Animal::create($animal->toArray());

    expect(Animal::count())->toBe(1);
});

it('can create an egg production record', function () {
    $farmer = Farmer::factory()->create();
    $animal = Animal::factory()->create(['farmer_id' => $farmer->id, 'type' => 'ayam']);

    $eggProduction = EggProduction::factory()->make([
        'animal_id' => $animal->id,
    ]);

    expect(EggProduction::count())->toBe(0);

    EggProduction::create($eggProduction->toArray());

    expect(EggProduction::count())->toBe(1);
});

it('can create a feed record', function () {
    $farmer = Farmer::factory()->create();
    $animal = Animal::factory()->create(['farmer_id' => $farmer->id]);

    $feedRecord = FeedRecord::factory()->make([
        'animal_id' => $animal->id,
    ]);

    expect(FeedRecord::count())->toBe(0);

    FeedRecord::create($feedRecord->toArray());

    expect(FeedRecord::count())->toBe(1);
});

it('can create a health record', function () {
    $farmer = Farmer::factory()->create();
    $animal = Animal::factory()->create(['farmer_id' => $farmer->id]);

    $healthRecord = HealthRecord::factory()->make([
        'animal_id' => $animal->id,
    ]);

    expect(HealthRecord::count())->toBe(0);

    HealthRecord::create($healthRecord->toArray());

    expect(HealthRecord::count())->toBe(1);
});

it('can update a farmer', function () {
    $farmer = Farmer::factory()->create(['name' => 'Old Name']);

    $farmer->update(['name' => 'New Name']);

    expect($farmer->fresh()->name)->toBe('New Name');
});

it('can delete a farmer', function () {
    $farmer = Farmer::factory()->create();

    expect(Farmer::count())->toBe(1);

    $farmer->delete();

    expect(Farmer::count())->toBe(0);
});

it('can retrieve farmers with their relationships', function () {
    $farmer = Farmer::factory()->create();
    Animal::factory()->count(3)->create(['farmer_id' => $farmer->id]);

    $farmer = Farmer::with('animals')->find($farmer->id);

    expect($farmer->animals)->toHaveCount(3);
});

it('can filter health records by type', function () {
    $farmer = Farmer::factory()->create();
    $animal = Animal::factory()->create(['farmer_id' => $farmer->id]);

    HealthRecord::factory()->create(['animal_id' => $animal->id, 'type' => 'vaksin']);
    HealthRecord::factory()->create(['animal_id' => $animal->id, 'type' => 'sakit']);
    HealthRecord::factory()->create(['animal_id' => $animal->id, 'type' => 'vaksin']);

    $vaksinRecords = HealthRecord::where('type', 'vaksin')->get();

    expect($vaksinRecords)->toHaveCount(2);
});

it('can calculate total egg production', function () {
    $farmer = Farmer::factory()->create();
    $animal = Animal::factory()->create(['farmer_id' => $farmer->id, 'type' => 'ayam']);

    EggProduction::factory()->create(['animal_id' => $animal->id, 'quantity' => 100]);
    EggProduction::factory()->create(['animal_id' => $animal->id, 'quantity' => 150]);
    EggProduction::factory()->create(['animal_id' => $animal->id, 'quantity' => 200]);

    $total = EggProduction::sum('quantity');

    expect($total)->toBe(450);
});

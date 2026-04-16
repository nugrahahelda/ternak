<?php

namespace App\Filament\Resources\Farmers\Pages;

use App\Filament\Resources\Farmers\FarmerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateFarmer extends CreateRecord
{
    protected static string $resource = FarmerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Create user first
        $user = User::create([
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
        ]);

        // Assign peternak role
        $user->assignRole('peternak');

        // Set user_id for farmer
        $data['user_id'] = $user->id;

        // Remove user fields from farmer data
        unset($data['user_name'], $data['user_email'], $data['user_password']);

        return $data;
    }
}

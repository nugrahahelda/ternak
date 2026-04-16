<?php

namespace App\Filament\Resources\Farmers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FarmerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peternak')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Peternak')
                            ->required(),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true),
                    ]),
                Section::make('Informasi Akun User')
                    ->schema([
                        TextInput::make('user_name')
                            ->label('Nama User')
                            ->required()
                            ->hiddenOn('edit'),
                        TextInput::make('user_email')
                            ->label('Email User')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true)
                            ->hiddenOn('edit'),
                        TextInput::make('user_password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->hiddenOn('edit'),
                    ])
                    ->hiddenOn('edit')
                    ->description('User dengan role "peternak" akan dibuat otomatis'),
            ]);
    }
}

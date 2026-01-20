<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                       TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state)) // Agar password di-hash otomatis (opsional tapi recommended)
                            ->required(fn (string $operation): bool => $operation === 'create') // Wajib diisi HANYA saat create
                            ->visibleOn('create'), // <--- INI KUNCINYA (Hanya tampil di halaman Create)
                        Select::make('role')
                            ->required()
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                            ])
                            ->default('user'), 
                    ]),
            ]);
    }
}

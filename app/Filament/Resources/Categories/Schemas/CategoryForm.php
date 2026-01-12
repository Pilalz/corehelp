<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Section;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', $state ? Str::slug($state) : null);
                            })
                            ->required(),
                        Hidden::make('slug')
                            ->required(),
                    ]),
                
            ]);
    }
}

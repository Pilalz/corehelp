<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('name')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->columnSpanFull(),
                        TextEntry::make('slug')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('assigned_to')
                    ->numeric(),
                Select::make('category_id')
                    ->relationship('category', 'name'),
                TextInput::make('subject')
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('attachments'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium'),
                Textarea::make('resolution_summary')
                    ->columnSpanFull(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}

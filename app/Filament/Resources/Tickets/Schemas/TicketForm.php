<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\FileUpload;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->columnSpan(['lg' => 8])
                            ->schema([
                                Section::make('Detailed Ticket')
                                    ->schema([
                                        TextInput::make('subject')
                                            ->required(),

                                        Textarea::make('content')
                                            ->required()
                                            ->columnSpanFull(),

                                        FileUpload::make('attachments')
                                            ->multiple()
                                            ->directory('tickets')
                                            ->disk('public'),

                                        Textarea::make('resolution_summary')
                                            ->columnSpanFull()
                                            ->visible(fn ($get) => $get('status') === 'solved')
                                            ->required(fn ($get) => $get('status') === 'solved'),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 4])
                            ->schema([
                                Section::make('Meta Data')
                                    ->schema([
                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->required(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->required(), 

                                        Select::make('status')
                                            ->required()
                                            ->live()
                                            ->options([
                                                'open' => 'Open',
                                                'solved' => 'Solved',
                                            ])
                                            ->default('open'),

                                        Select::make('priority')
                                            ->required()
                                            ->options([
                                                'low' => 'Low',
                                                'medium' => 'Medium',
                                                'high' => 'High',
                                            ])
                                            ->default('medium'),

                                        DateTimePicker::make('resolved_at')
                                            ->visible(fn ($get) => $get('status') === 'solved')
                                            ->required(fn ($get) => $get('status') === 'solved'),
                                    ]),
                            ]),
                    ]),

                // TextInput::make('assigned_to')
                //     ->numeric(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                // TextColumn::make('assigned_to')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('category.name'),
                TextColumn::make('subject')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'primary',
                        're-open' => 'primary',
                        'solved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'medium' => 'warning',
                        'low' => 'success',
                        'high' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),
                TextColumn::make('resolved_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'solved' => 'Solved',
                        'rejected' => 'Rejected',
                    ])
                    ->preload(),
                SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->preload(),
            ])
            ->recordActions([
                Action::make('reply')
                    ->label('Chat')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('primary')
                    ->url(fn ($record): string => route('filament.admin.resources.tickets.chat', $record)),
                    
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

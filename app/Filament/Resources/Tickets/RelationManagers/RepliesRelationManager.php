<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema; 
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;

// --- PERBAIKAN IMPORT (SEMUA DARI GLOBAL ACTIONS) ---
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction; 
use Filament\Actions\BulkActionGroup; // PENTING: Ini yang bikin error tadi

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';
    protected static ?string $title = 'Riwayat Percakapan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('content')
                    ->label('Isi Balasan')
                    ->required()
                    ->columnSpanFull(),
                
                FileUpload::make('attachments')
                    ->multiple()
                    ->directory('ticket-replies')
                    ->disk('public')
                    ->columnSpanFull(),
                    
                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengirim')
                    ->description(fn ($record) => $record->user->role === 'staff' ? 'Staff / Admin' : 'User')
                    ->color(fn ($record) => $record->user->role === 'staff' ? 'warning' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('content')
                    ->html()
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Balas Tiket')
                    ->modalHeading('Kirim Balasan')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Gunakan BulkActionGroup dari namespace global
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\IconEntry;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class ArticleInfolist
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
                                Section::make('Detailed Article')
                                    ->description('Contains content or documents that have been uploaded.')
                                    ->schema([
                                        TextEntry::make('title')
                                            ->weight(FontWeight::Bold),

                                        TextEntry::make('slug')
                                            ->label('URL Slug')
                                            ->fontFamily('mono')
                                            ->color('gray')
                                            ->icon('heroicon-m-link')
                                            ->copyable(),

                                        TextEntry::make('content')
                                            ->html()
                                            ->prose()
                                            ->visible(fn ($record) => $record->file_path === null),

                                        TextEntry::make('file_path')
                                            ->label('Dokumen PDF')
                                            ->visible(fn ($record) => $record->file_path != null)
                                            ->formatStateUsing(fn () => 'Download / View Dokumen')
                                            ->icon('heroicon-o-document-text')
                                            ->color('primary')
                                            ->url(fn ($record) => Storage::url($record->file_path))
                                            ->openUrlInNewTab(),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 4])
                            ->schema([
                                Section::make('Meta Data')
                                    ->schema([
                                        TextEntry::make('is_published')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Published' : 'Draft')
                                            ->color(fn (bool $state) => $state ? 'success' : 'warning'),

                                        TextEntry::make('category.name')
                                            ->label('Kategori')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('helpful_count')
                                            ->label('Helpful Content')
                                            ->icon('heroicon-m-hand-thumb-up')
                                            ->color('success')
                                            ->numeric(),

                                        TextEntry::make('created_at')
                                            ->label('Dibuat Pada')
                                            ->dateTime('d M Y, H:i')
                                            ->color('gray'),

                                        TextEntry::make('updated_at')
                                            ->label('Terakhir Update')
                                            ->dateTime('d M Y, H:i')
                                            ->color('gray'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
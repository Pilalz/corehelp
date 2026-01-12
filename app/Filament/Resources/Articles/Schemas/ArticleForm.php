<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;

class ArticleForm
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
                                    ->schema([
                                        TextInput::make('title')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, $set) {
                                                $set('slug', $state ? Str::slug($state) : null);
                                            })
                                            ->required(),

                                        Hidden::make('slug')
                                            ->required(),

                                        Radio::make('input_type')
                                            ->label('Article Source')
                                            ->options([
                                                'manual' => 'Text Editor',
                                                'upload' => 'Upload File (PDF/Word)',
                                            ])
                                            ->default('upload')
                                            ->inline()
                                            ->live()
                                            ->columnSpanFull()
                                            ->dehydrated(false)
                                            ->afterStateUpdated(function ($state, $set) {
                                                if ($state === 'manual') $set('file_path', null);
                                                if ($state === 'upload') $set('content', null);
                                            }),

                                        RichEditor::make('content')
                                            ->label('Isi Artikel')
                                            ->columnSpanFull()
                                            ->hidden(fn ($get) => $get('input_type') === 'upload')
                                            ->required(fn ($get) => $get('input_type') === 'manual')
                                            ->fileAttachmentsDirectory('articles-images')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsVisibility('public')
                                            ->columnSpanFull(),
                                        FileUpload::make('file_path')
                                            ->label('Upload File (PDF)')
                                            ->columnSpanFull()
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->directory('articles-docs')
                                            ->disk('public')
                                            ->hidden(fn ($get) => $get('input_type') === 'manual')
                                            ->required(fn ($get) => $get('input_type') === 'upload'),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 4])
                            ->schema([
                                Section::make('Meta Data')
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->columnSpanFull()
                                            ->required(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->required(), 

                                        TextInput::make('helpful_count')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}

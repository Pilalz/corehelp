<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class TicketInfolist
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
                                        TextEntry::make('subject')
                                            ->weight(FontWeight::Bold),

                                        TextEntry::make('content')
                                            ->html()
                                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString(nl2br(e($state)))),

                                        TextEntry::make('attachments')
                                            ->label('Attachments')
                                            ->html()
                                            ->formatStateUsing(function ($state) {
                                                // normalize: if stored as JSON string, decode it; if comma/newline separated string, split it
                                                    if (is_string($state)) {
                                                    $decoded = json_decode($state, true);
                                                    if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                                                        $state = $decoded;
                                                    } else {
                                                        // split by comma or new line
                                                        $parts = preg_split('/[\r\n,]+/', $state);
                                                        $parts = array_map(fn($s) => trim($s, " \t\n\r\"'"), $parts);
                                                        $parts = array_filter($parts, fn($s) => $s !== '');
                                                        $state = array_values($parts);
                                                    }
                                                }
                                                    // If state is a single object (attachment) or an associative array (single attachment), wrap it
                                                    if (is_object($state)) {
                                                        $state = [$state];
                                                    } elseif (is_array($state) && !array_is_list($state)) {
                                                        // associative array -> single attachment
                                                        $state = [$state];
                                                    }

                                                    if (empty($state) || (!is_array($state) && !is_object($state))) {
                                                    return '<span class="text-gray-500">No attachments</span>';
                                                }

                                                $items = '';
                                                foreach ((array) $state as $att) {
                                                    if (is_array($att) || is_object($att)) {
                                                        $name = $att['name'] ?? ($att->name ?? basename($att['path'] ?? $att->path ?? ''));
                                                        $path = $att['path'] ?? ($att->path ?? null);
                                                    } else {
                                                        $raw = (string) $att;
                                                        $name = basename($raw);

                                                        // try different candidate locations if file name only
                                                        $candidates = [$raw, 'tickets/'.$raw, 'ticket-replies/'.$raw, 'articles/'.$raw, 'attachments/'.$raw];
                                                        $found = null;
                                                        foreach ($candidates as $cand) {
                                                            if ($cand && Storage::disk('public')->exists($cand)) {
                                                                $found = $cand;
                                                                break;
                                                            }
                                                        }
                                                        $path = $found ?? $raw;
                                                    }

                                                    $url = ($path && Storage::disk('public')->exists($path)) ? Storage::url($path) : '#';
                                                    $mime = null;
                                                    try { $mime = ($path && Storage::disk('public')->exists($path)) ? Storage::mimeType($path) : null; } catch (\Exception $e) { $mime = null; }

                                                    if ($mime && Str::startsWith($mime, 'image/')) {
                                                        $items .= "<a href=\"{$url}\" target=\"_blank\" class=\"inline-block mr-2 mb-2\"><img src=\"{$url}\" alt=\"{$name}\" style=\"height:64px;max-width:160px;object-fit:cover;border-radius:6px;border:1px solid #e5e7eb;\"></a>";
                                                    } else {
                                                        $items .= "<div class=\"mb-2\"><a href=\"{$url}\" target=\"_blank\" class=\"text-sm text-blue-600 underline\">{$name}</a></div>";
                                                    }
                                                }

                                                return $items;
                                            }),

                                        TextEntry::make('resolution_summary')
                                            ->html()
                                            ->prose()
                                            ->visible(fn ($get) => $get('status') === 'solved'),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 4])
                            ->schema([
                                Section::make('Meta Data')
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->weight(FontWeight::Bold)
                                            ->label('From'),                                    

                                        TextEntry::make('category.name')
                                            ->label('Category')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'open' => 'primary',
                                                're-open' => 'primary',
                                                'solved' => 'success',
                                                'rejected' => 'danger',
                                            })
                                            ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                                        TextEntry::make('priority')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'draft' => 'gray',
                                                'medium' => 'warning',
                                                'low' => 'success',
                                                'high' => 'danger',
                                            })
                                            ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                                        TextEntry::make('resolved_at')
                                            ->label('Solved At')
                                            ->dateTime('d M Y, H:i')
                                            ->color('gray')
                                            ->visible(fn ($get) => $get('status') === 'solved'),

                                        TextEntry::make('created_at')
                                            ->label('Created At')
                                            ->dateTime('d M Y, H:i')
                                            ->color('gray'),

                                        TextEntry::make('updated_at')
                                            ->label('Last Updated')
                                            ->dateTime('d M Y, H:i')
                                            ->color('gray'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    // public function mount(): void
    // {
    //     parent::mount();
        
    //     FilamentView::registerRenderHook(
    //         PanelsRenderHook::HEAD_END,
    //         fn (): string => '<style>
    //             .fi-header {
    //                 background-color: white !important;
    //                 border: 1px solid #e5e7eb !important;
    //                 box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
    //                 border-radius: 0.75rem !important;
    //                 padding: 10px 20px !important;
    //                 margin: 0 !important;
    //             }
    //         </style>',
    //         scopes: [static::class]
    //     );
    // }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

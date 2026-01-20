<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Ticket;
use App\Models\User;

class StatsOverview extends StatsOverviewWidget
{
    // Agar widget ini muncul paling atas
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Tickets', Ticket::count())
                ->description('Semua tiket yang masuk')
                ->descriptionIcon('heroicon-m-ticket')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Open Tickets', Ticket::where('status', 'open')->count())
                ->description('Menunggu respon')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('Solved Tickets', Ticket::where('status', 'solved')->count())
                ->description('Masalah terselesaikan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}

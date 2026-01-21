<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use App\Models\Ticket;

class ChatTicket extends Page
{
    use InteractsWithRecord;

    protected static string $resource = TicketResource::class;

    protected string $view = 'filament.resources.tickets.pages.chat-ticket';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        // Judul halaman jadi dinamis, misal: "Chat: Komputer Rusak"
        return 'Chat: ' . $this->record->subject;
    }
}

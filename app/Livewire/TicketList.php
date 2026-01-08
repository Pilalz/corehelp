<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class TicketList extends Component
{
    use WithPagination;

    public function render()
    {
        $tickets = auth()->user()->tickets()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('livewire.ticket-list', [
            'tickets' => $tickets
        ])->layout('layouts.app');
    }
}

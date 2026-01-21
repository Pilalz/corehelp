<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;
use Livewire\WithFileUploads;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class AdminTicketChat extends Component implements HasForms
{
    use WithFileUploads;
    use InteractsWithForms;

    public Ticket $ticket;
    
    public $replyContent = '';
    public $replyAttachments = [];
    public $fileInputId = 1;

    public function mount(Ticket $record)
    {
        $this->ticket = $record;
    }

    public function saveReply()
    {
        $this->validate([
            'replyContent' => 'required',
            'replyAttachments.*' => 'nullable|file|max:10240',
        ]);

        // Logic Upload (Sama persis dengan frontend)
        $paths = [];
        foreach ($this->replyAttachments as $file) {
             if (!is_string($file)) {
                $stored = $file->store('ticket-replies', 'public');
                $paths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored,
                ];
            }
        }

        TicketReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(), // ID Admin yang login
            'content' => $this->replyContent,
            'attachments' => $paths,
        ]);

        $this->reset(['replyContent', 'replyAttachments']);
        $this->fileInputId++;
        $this->ticket->refresh();
    }

    public function render()
    {
        return view('livewire.admin-ticket-chat');
    }
}

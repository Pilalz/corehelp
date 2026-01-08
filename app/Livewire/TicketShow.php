<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;
use Livewire\WithFileUploads;

class TicketShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $replyContent;
    public $replyAttachments = [];

    public function mount($id)
    {
        // Cari tiket, dan pastikan milik user yang login (Security)
        $this->ticket = Ticket::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['replies.user', 'user']) // Load relasi replies & user
            ->firstOrFail();
    }

    public function saveReply()
    {
        $this->validate([
            'replyContent' => 'required',
        ]);

        // Simpan lampiran balasan (jika ada)
        $paths = [];
        foreach ($this->replyAttachments as $file) {
            $paths[] = $file->store('ticket-replies', 'public');
        }

        TicketReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
            'attachments' => $paths,
        ]);
        
        // Update status tiket jadi 'open' lagi jika user membalas (biar admin notic)
        // Opsional, tergantung flow kamu
        // $this->ticket->update(['status' => 'open']);

        $this->replyContent = '';
        $this->replyAttachments = [];
        $this->ticket->refresh(); // Refresh data biar komen baru muncul
    }

    public function render()
    {
        return view('livewire.ticket-show')->layout('layouts.app');
    }
}

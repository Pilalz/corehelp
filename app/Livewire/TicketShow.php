<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;

class TicketShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $replyContent = '';
    public $replyAttachments = [];

    public $fileInputId = 1;

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
            'replyAttachments.*' => 'nullable|file|mimes:png,jpg,jpeg,gif,pdf,doc,docx|max:2048',
        ]);

        // Simpan lampiran balasan (jika ada) — simpan nama asli + path
        $paths = [];
        foreach ($this->replyAttachments as $file) {
            if (is_string($file)) {
                // already stored path (fallback)
                $paths[] = [
                    'name' => basename($file),
                    'path' => $file,
                ];
                continue;
            }

            $stored = $file->store('ticket-replies', 'public');
            $paths[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $stored,
            ];
        }

        TicketReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
            'attachments' => $paths,
        ]);

        $this->reset(['replyContent', 'replyAttachments']);

        $this->fileInputId++;

        $this->ticket->refresh();
    }

    public function markAsSolved()
    {
        $this->ticket->update([
            'status' => 'closed',
        ]);

        $this->ticket->refresh();

        Notification::make()
            ->success()
            ->title('Terima kasih!')
            ->body('Tiket telah ditutup karena masalah terselesaikan.')
            ->send();
    }

    public function markAsUnsolved()
    {
        $this->dispatch('focus-reply-box');
        
        Notification::make()
            ->info()
            ->title('Unsolved')
            ->body('Silakan jelaskan kendala yang masih Anda alami di bawah.')
            ->send();
    }

    public function render()
    {
        return view('livewire.ticket-show')->layout('layouts.app');
    }
}

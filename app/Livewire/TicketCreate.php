<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Ticket;
use Livewire\WithFileUploads;

class TicketCreate extends Component
{
    use WithFileUploads;

    public $subject;
    public $category_id;
    public $content;
    public $attachments = [];

    protected $rules = [
        'subject' => 'required|min:5',
        'category_id' => 'required',
        'content' => 'required|min:10',
        'attachments.*' => 'image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $filePaths = [];
        foreach ($this->attachments as $file) {            
            $filePaths[] = $file->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'subject' => $this->subject,
            'content' => $this->content,
            'attachments' => $filePaths,
            'status' => 'open'
        ]);

        session()->flash('message', 'Tiket berhasil dibuat!');
        return redirect()->route('tickets.index');
    }

    public function render()
    {
        return view('livewire.ticket-create', [
            'categories' => Category::all()
        ])->layout('layouts.app');
    }
}

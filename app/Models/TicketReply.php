<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    use HasFactory;

    protected $table = 'ticket_replies';

    protected $fillable = [        
        'ticket_id',
        'user_id',
        'content',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

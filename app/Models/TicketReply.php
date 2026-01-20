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

    protected static function booted(): void
    {
        static::created(function (TicketReply $reply) {
            $reply->load('ticket');

            // Admin balas = Solved
            if ($reply->user_id !== $reply->ticket->user_id && $reply->user->role === 'admin') {
                $reply->ticket->update([
                    'status' => 'solved',
                    'resolved_at' => now(),
                ]);
            }

            // User balas = Re-Open
            elseif ($reply->user_id === $reply->ticket->user_id && $reply->ticket->status === 'solved') {
                $reply->ticket->update([
                    'status' => 're-open',
                    'resolved_at' => null,
                ]);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Category;
use App\Models\TicketReply;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [        
        'user_id',
        'assigned_to',
        'category_id',
        'subject',
        'content',
        'status',
        'priority',
        'resolution_summary',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Staff yang ditunjuk
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Komentar/Balasan
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}

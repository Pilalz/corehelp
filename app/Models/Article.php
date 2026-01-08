<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [        
        'category_id',
        'title',
        'slug',
        'content',
        'is_published',
        'helpful_count',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Jobs\ExtractPdfText;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [        
        'category_id',
        'title',
        'slug',
        'file_path',
        'content',
        'is_published',
        'helpful_count',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Article $article) {
            \Illuminate\Support\Facades\Log::info('Event Saved Triggered for ID: ' . $article->id);

            if ($article->file_path && ($article->wasRecentlyCreated || $article->wasChanged('file_path'))) {
                
                \Illuminate\Support\Facades\Log::info('Dispatching Job for ID: ' . $article->id);
                
                ExtractPdfText::dispatch($article); 
            }
        });
    }
}

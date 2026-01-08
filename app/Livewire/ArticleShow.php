<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;

class ArticleShow extends Component
{
    public Article $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.article-show')->layout('layouts.app');
    }
}

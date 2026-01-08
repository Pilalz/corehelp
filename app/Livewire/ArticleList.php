<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;

class ArticleList extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $articles = Article::where('is_published', true)
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('content', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(9);

        return view('livewire.article-list', [
            'articles' => $articles
        ])->layout('layouts.app');
    }
}

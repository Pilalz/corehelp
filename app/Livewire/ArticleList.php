<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ArticleList extends Component
{
    use WithPagination;

    #[Url] // Agar search tersimpan di URL (bisa di-share)
    public $search = '';

    #[Url] // Agar filter kategori tersimpan di URL
    public $category_id = '';

    public function updatedSearch()
    {
        $this->resetPage(); // Reset ke halaman 1 tiap kali ngetik
    }

    public function selectCategory($id)
    {
        // Kalau diklik kategori yg sama, reset (jadi "All")
        // Kalau beda, set kategori baru
        $this->category_id = ($this->category_id == $id) ? '' : $id;
        $this->resetPage();
    }

    public function render()
    {
        // Query Utama
        $query = Article::query()
            ->with('category')
            ->where('is_published', true);

        // Filter Pencarian
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter Kategori
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        return view('livewire.article-list', [
            'articles' => $query->latest()->paginate(9), // Tampilkan 9 per halaman
            'categories' => Category::has('articles')->get(), // Ambil kategori yg punya artikel saja
        ])->layout('layouts.app');
    }
}
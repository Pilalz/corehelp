<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ArticleList;
use App\Livewire\ArticleShow;
use App\Livewire\TicketList;
use App\Livewire\TicketCreate;
use App\Livewire\TicketShow;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', ArticleList::class)->name('article.index');
Route::get('/article/{slug}', ArticleShow::class)->name('article.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tickets', TicketList::class)->name('tickets.index');
    Route::get('/tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('/tickets/{id}', TicketShow::class)->name('tickets.show');
});

require __DIR__.'/auth.php';

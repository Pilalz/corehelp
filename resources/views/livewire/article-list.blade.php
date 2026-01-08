<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Pusat Bantuan & Panduan</h1>
            <input wire:model.live.debounce.300ms="search" type="text" 
                class="w-full max-w-lg rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                placeholder="Cari masalah anda (contoh: WiFi, Login)...">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="block p-6 bg-white rounded-lg shadow hover:shadow-md transition">
                    <span class="text-xs font-semibold text-indigo-600 uppercase">
                        {{ $article->category->name }}
                    </span>
                    <h2 class="mt-2 text-xl font-bold text-gray-900">{{ $article->title }}</h2>
                    <p class="mt-2 text-gray-600 line-clamp-3">
                        {{-- Strip tags untuk membuang HTML saat preview --}}
                        {{ Str::limit(strip_tags($article->content), 100) }}
                    </p>
                </a>
            @empty
                <div class="col-span-3 text-center text-gray-500">
                    Tidak ada artikel ditemukan. <br>
                    <a href="{{ route('tickets.create') }}" class="text-indigo-600 underline">Ajukan Tiket Bantuan?</a>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
</div>
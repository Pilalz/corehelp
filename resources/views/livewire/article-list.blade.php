<div class="min-h-screen bg-gray-50 pb-12">
    
    <div class="bg-indigo-700 py-16 px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-extrabold text-white sm:text-4xl">
            Bagaimana kami bisa membantu?
        </h1>
        <p class="mt-4 text-xl text-indigo-100">
            Cari panduan, tutorial, dan solusi masalah anda di sini.
        </p>

        <div class="mt-8 max-w-xl mx-auto">
            <div class="relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="block w-full rounded-full border-0 py-4 pl-10 text-gray-200 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm sm:leading-6 shadow-lg" 
                    placeholder="Cari masalah anda (contoh: WiFi, Login)...">
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex flex-wrap gap-2 justify-center mb-8">
            <button 
                wire:click="$set('category_id', '')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border
                {{ $category_id == '' 
                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' 
                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                Semua Topik
            </button>

            @foreach($categories as $cat)
                <button 
                    wire:click="selectCategory({{ $cat->id }})"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border
                    {{ $category_id == $cat->id 
                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' 
                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="group relative flex flex-col bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100">
                    
                    {{-- <div class="h-40 bg-gray-200 w-full object-cover"></div> --}}
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $article->category->name }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                            {{ $article->title }}
                        </h3>

                        <p class="mt-3 text-sm text-gray-500 line-clamp-3">
                            {{ Str::limit(strip_tags($article->content), 100) }}
                        </p>

                        <div class="mt-auto pt-4 flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                {{ $article->helpful_count }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Tidak ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ganti kata kunci atau pilih kategori lain.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</div>
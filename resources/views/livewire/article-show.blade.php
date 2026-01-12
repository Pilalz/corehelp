<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            
            <a href="{{ route('article.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Kembali</a>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
            <div class="text-sm text-gray-500 mb-6">
                Kategori: {{ $article->category->name }} &bull; Diupdate: {{ $article->updated_at->format('d M Y') }}
            </div>

            <hr class="mb-6">

            @if($article->file_path)
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 300) }}
                    </p>

                    @php
                        $fileUrl = Storage::url($article->file_path);
                        $mime = null;
                        try {
                            $mime = Storage::mimeType($article->file_path);
                        } catch (Exception $e) {
                            $mime = null;
                        }
                    @endphp

                    @if($mime && Str::startsWith($mime, 'image/'))
                        <img src="{{ $fileUrl }}" alt="{{ $article->title }}" class="w-full rounded shadow mb-4" />
                    @elseif($mime === 'application/pdf' || ($mime && Str::contains($mime, 'pdf')))
                        <div class="w-full h-[800px] mb-4">
                            <iframe src="{{ $fileUrl }}" class="w-full h-full border" frameborder="0"></iframe>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Dokumen tersedia. <a href="{{ $fileUrl }}" target="_blank" class="font-bold underline hover:text-blue-600">Download / Lihat Dokumen Asli</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="prose max-w-none text-gray-800 leading-relaxed">
                    {!! $article->content !!}
                </div>
            @endif

            <div class="mt-10 pt-6 border-t border-gray-100">
                <p class="text-center text-gray-500">Apakah artikel ini membantu?</p>
            </div>

        </div>
    </div>
</div>
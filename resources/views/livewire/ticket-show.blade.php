<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
            <h1 class="text-2xl font-bold text-gray-900">{{ $this->ticket->subject }}</h1>
            <div class="text-sm text-gray-500 mt-1 mb-4">
                {{ $this->ticket->created_at->format('d M Y, H:i') }} &bull; Status: <span class="font-bold">{{ strtoupper($this->ticket->status) }}</span>
            </div>
            
            <div class="prose max-w-none text-gray-800">
                {!! nl2br(e($this->ticket->content)) !!}
            </div>

            @if($this->ticket->attachments)
                <div class="mt-4 flex gap-2 flex-wrap">
                    @foreach($this->ticket->attachments as $attachment)
                        @php
                            // support old string-path format and new object format
                            if (is_array($attachment) || is_object($attachment)) {
                                $name = $attachment['name'] ?? $attachment->name ?? basename($attachment['path'] ?? '');
                                $path = $attachment['path'] ?? $attachment->path ?? null;
                            } else {
                                $name = basename($attachment);
                                $path = $attachment;
                            }

                            $url = $path ? Storage::url($path) : '#';
                            $mime = null;
                            try { $mime = $path ? Storage::mimeType($path) : null; } catch (Exception $e) { $mime = null; }
                        @endphp

                        @if($mime && Str::startsWith($mime, 'image/'))
                            <a href="{{ $url }}" target="_blank">
                                <img src="{{ $url }}" class="h-24 w-24 object-cover rounded border hover:opacity-75" alt="{{ $name }}">
                            </a>
                        @elseif($mime && Str::contains($mime, 'pdf'))
                            <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 border rounded">
                                <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2h6l4 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z"/></svg>
                                <span class="text-xs">{{ $name }}</span>
                            </a>
                        @else
                            <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 border rounded text-sm">
                                <svg class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2h6l4 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z"/></svg>
                                <span class="truncate">{{ $name }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <h3 class="font-bold text-lg text-gray-700">Diskusi / Balasan</h3>
            
            @foreach($this->ticket->replies as $reply)
                <div class="bg-white p-4 rounded-lg shadow {{ $reply->user->isStaff() ? 'border-l-4 border-orange-400 bg-orange-50' : '' }}">
                    <div class="flex justify-between mb-2">
                        <span class="font-bold {{ $reply->user->isStaff() ? 'text-orange-600' : 'text-indigo-600' }}">
                            {{ $reply->user->name }} {{ $reply->user->isStaff() ? '(Staff)' : '' }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-gray-800">
                        
                        {!! nl2br(e($reply->content)) !!}
                    </div>
                    @if($reply->attachments)
                        <div class="mt-2 flex gap-2 flex-wrap">
                            @foreach($reply->attachments as $attachment)
                                @php
                                    if (is_array($attachment) || is_object($attachment)) {
                                        $name = $attachment['name'] ?? $attachment->name ?? basename($attachment['path'] ?? '');
                                        $path = $attachment['path'] ?? $attachment->path ?? null;
                                    } else {
                                        $name = basename($attachment);
                                        $path = $attachment;
                                    }
                                    $url = $path ? Storage::url($path) : '#';
                                    $mime = null;
                                    try { $mime = $path ? Storage::mimeType($path) : null; } catch (Exception $e) { $mime = null; }
                                @endphp

                                @if($mime && Str::startsWith($mime, 'image/'))
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}" class="h-16 w-16 object-cover rounded border hover:opacity-75" alt="{{ $name }}">
                                    </a>
                                @else
                                    <a href="{{ $url }}" target="_blank" class="text-blue-500 text-xs underline">{{ $name }}</a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($this->ticket->status !== 'closed')
        <div class="bg-white p-6 shadow sm:rounded-lg">
            <h3 class="font-bold text-md mb-2">Balas Tiket</h3>
            <form wire:submit.prevent="saveReply">
                <textarea wire:model="replyContent" class="w-full rounded border-gray-300" rows="3" placeholder="Tulis balasan..."></textarea>
                @error('replyContent') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

                <div class="mt-2">
                    <input type="file" wire:model="replyAttachments" multiple class="text-sm">
                    @error('replyAttachments.*') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

                    <!-- show selected filenames (client-side, Livewire tempFile objects) -->
                    @if(!empty($replyAttachments))
                        <div class="mt-2 text-sm text-gray-600">
                            <strong>Selected files:</strong>
                            <ul class="list-disc list-inside">
                                @foreach($replyAttachments as $i => $file)
                                    <li>{{ is_object($file) && method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : (is_array($file) && isset($file['name']) ? $file['name'] : (string)$file) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-3">
                    <button type="submit" wire:loading.attr="disabled" wire:target="replyAttachments" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Kirim Balasan</button>
                </div>

                <div wire:loading wire:target="replyAttachments" class="text-sm text-gray-500 mt-2">Mengunggah lampiran...</div>
            </form>
        </div>
        @else
            <div class="bg-gray-100 p-4 text-center rounded text-gray-500">
                Tiket ini sudah ditutup. Tidak bisa membalas lagi.
            </div>
        @endif

    </div>
</div>
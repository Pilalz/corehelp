<div class="space-y-6">

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
            <div class="bg-white p-4 rounded-lg shadow {{ $reply->user->isUser() ? 'border-l-4 border-indigo-500 bg-indigo-50' : 'border-l-4 border-orange-400 bg-orange-50' }}">
                <div class="flex justify-between mb-2">
                    @php
                        $colorClass = match(true) {
                            $reply->user->isUser()  => 'text-indigo-600',
                            $reply->user->isAdmin() => 'text-orange-600',
                            default                 => 'text-green-600', // Warna untuk role ke-3 (misal: Support)
                        };

                        $roleLabel = match(true) {
                            $reply->user->isMe()    => '(You)',
                            $reply->user->isUser()  => '(User)',
                            $reply->user->isAdmin() => '(Admin)',
                            default                 => '(Support)',
                        };
                    @endphp
                    <span class="font-bold {{ $colorClass }}">
                        {{ $reply->user->name }} {{ $roleLabel }}
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
            <form wire:submit.prevent="saveReply">
                <div class="mb-3">
                    <textarea 
                        wire:model="replyContent" 
                        wire:key="admin-reply-{{ $fileInputId }}"
                        class="w-full rounded-lg p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                        rows="3" 
                        placeholder="Tulis balasan kepada user..."></textarea>
                </div>

                <div class="flex justify-between items-center">
                    <input 
                        type="file" 
                        wire:model="replyAttachments" 
                        id="admin-attachments-{{ $fileInputId }}" 
                        multiple 
                        class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        Kirim Balasan
                    </button>
                </div>
                
                <div wire:loading wire:target="saveReply" class="text-xs text-gray-500 mt-2">
                    Sedang mengirim...
                </div>
            </form>
        </div>
    @else
        <div class="bg-gray-100 p-4 text-center rounded text-gray-500">
            Tiket ini sudah ditutup. Tidak bisa membalas lagi.
        </div>
    @endif
</div>
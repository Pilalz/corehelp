<div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
    <h2 class="text-lg font-bold mb-4">Riwayat Percakapan</h2>

    <div class="space-y-6 mb-8 max-h-125 overflow-y-auto pr-2">
        @foreach($ticket->replies as $reply)
            {{-- Logic warna dibalik: Admin di Kanan (Indigo), User di Kiri (Orange) --}}
            <div class="flex {{ $reply->user->isUser() ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-[80%] rounded-lg p-4 shadow-sm {{ $reply->user->isUser() ? 'bg-white border-gray-200 border' : 'bg-indigo-50 border-indigo-200 border' }}">
                    
                    <div class="flex justify-between items-center mb-2 gap-4">
                        <span class="font-bold text-sm {{ $reply->user->isUser() ? 'text-orange-600' : 'text-indigo-700' }}">
                            {{ $reply->user->name }}
                            <span class="text-xs font-normal text-gray-500">({{ $reply->user->role }})</span>
                        </span>
                        <span class="text-xs text-gray-400">{{ $reply->created_at->format('d M H:i') }}</span>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-800">
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
            </div>
        @endforeach
    </div>

    <div class="border-t pt-4">
        <form wire:submit.prevent="saveReply">
            <div class="mb-3">
                <textarea 
                    wire:model="replyContent" 
                    wire:key="admin-reply-{{ $fileInputId }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
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
</div>
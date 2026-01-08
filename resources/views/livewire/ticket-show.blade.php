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
                    @foreach($this->ticket->attachments as $img)
                        <a href="{{ Storage::url($img) }}" target="_blank">
                            <img src="{{ Storage::url($img) }}" class="h-24 w-24 object-cover rounded border hover:opacity-75">
                        </a>
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
                        {{ $reply->content }}
                    </div>
                    @if($reply->attachments)
                        <div class="mt-2 flex gap-2">
                            @foreach($reply->attachments as $img)
                                <a href="{{ Storage::url($img) }}" target="_blank" class="text-blue-500 text-xs underline">Lihat Lampiran</a>
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
                <div class="flex justify-between mt-2 items-center">
                    <input type="file" wire:model="replyAttachments" multiple class="text-sm">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Kirim Balasan</button>
                </div>
            </form>
        </div>
        @else
            <div class="bg-gray-100 p-4 text-center rounded text-gray-500">
                Tiket ini sudah ditutup. Tidak bisa membalas lagi.
            </div>
        @endif

    </div>
</div>
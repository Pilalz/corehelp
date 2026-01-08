<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tiket Saya</h2>
            <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                + Buat Tiket Baru
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Subject</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $ticket->subject }}
                                    <div class="text-xs text-gray-400 truncate w-48">{{ Str::limit($ticket->content, 50) }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $ticket->category->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    {{-- Badge Warna-Warni --}}
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $ticket->status === 'open' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $ticket->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium text-indigo-600 hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">Belum ada tiket.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
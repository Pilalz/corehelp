<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Buat Tiket Baru</h2>

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Subjek Masalah</label>
                    <input type="text" wire:model="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select wire:model="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Detail Masalah</label>
                    <textarea wire:model="content" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Lampiran (Screenshot/Foto)</label>
                    <input type="file" wire:model="attachments" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <div wire:loading wire:target="attachments" class="text-sm text-gray-500 mt-1">Uploading...</div>
                    @error('attachments.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Kirim Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
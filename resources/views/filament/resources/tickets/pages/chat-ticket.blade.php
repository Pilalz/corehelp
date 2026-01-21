<x-filament-panels::page>
    {{-- Memanggil component Livewire chat dan mengoper data tiket --}}
    @livewire('admin-ticket-chat', ['record' => $record])
</x-filament-panels::page>
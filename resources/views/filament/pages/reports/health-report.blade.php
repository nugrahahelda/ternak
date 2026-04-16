<x-filament-panels::page>
    <form wire:submit="$refresh">
        {{ $this->form }}
    </form>

    {{ $this->table }}
</x-filament-panels::page>

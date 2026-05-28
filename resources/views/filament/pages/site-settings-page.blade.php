<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        <div class="flex justify-end mt-6">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

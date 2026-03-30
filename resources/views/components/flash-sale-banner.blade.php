@php
    $flashSale = \App\Models\FlashSale::active()->with(['items.product'])->first();
@endphp

@if($flashSale)
<div x-data="{ open: false }"
     class="bg-gray-950 border-b border-zinc-800">

    {{-- Compact Bar --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6"
         x-data="flashCountdown({{ $flashSale->ends_at->timestamp }})"
         x-init="start()">

        <div class="flex items-center gap-3 h-11">

            {{-- Toggle expand --}}
            <button @click="open = !open"
                class="flex items-center gap-2.5 flex-1 min-w-0 text-left group">

                {{-- Lightning icon --}}
                <div class="w-5 h-5 bg-amber-400 rounded flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3 text-gray-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>

                {{-- Name --}}
                <span class="text-white text-xs font-semibold truncate">{{ $flashSale->name }}</span>

                {{-- Separator --}}
                <span class="text-zinc-700 shrink-0 hidden sm:block">·</span>

                {{-- Countdown --}}
                <template x-if="!expired">
                    <div class="items-center gap-1 shrink-0 hidden sm:flex">
                        <span class="text-zinc-500 text-xs">Berakhir</span>
                        <span class="text-amber-400 text-xs font-mono font-semibold tabular-nums"
                              x-text="hours + ':' + minutes + ':' + seconds"></span>
                    </div>
                </template>
                <template x-if="expired">
                    <span class="text-red-400 text-xs font-medium shrink-0">Berakhir</span>
                </template>

                {{-- Mobile countdown --}}
                <template x-if="!expired">
                    <span class="text-amber-400 text-xs font-mono font-semibold tabular-nums shrink-0 sm:hidden"
                          x-text="hours + ':' + minutes + ':' + seconds"></span>
                </template>

            </button>

            {{-- Produk count label --}}
            <span class="text-zinc-600 text-xs shrink-0 hidden sm:block">{{ $flashSale->items->count() }} produk</span>

            {{-- Dropdown toggle button --}}
            <button @click.stop="open = !open"
                class="text-zinc-500 hover:text-white transition shrink-0 p-1">
                <svg class="w-4 h-4 transition-transform duration-200"
                     :class="open ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- Dropdown Product Strip --}}
    @if($flashSale->items->isNotEmpty())
    <div x-show="open"
         x-collapse
         class="border-t border-zinc-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex gap-2.5 overflow-x-auto pb-1"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($flashSale->items as $item)
                    @if($item->product?->is_active)
                    <a href="{{ route('shop.show', $item->product->slug) }}"
                       class="flex-shrink-0 flex items-center gap-2.5 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl px-3 py-2 transition group w-48">

                        {{-- Image --}}
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-zinc-800 shrink-0">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <p class="text-white text-xs font-medium truncate leading-tight">{{ $item->product->name }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-amber-400 text-xs font-semibold">
                                    Rp {{ number_format($item->flash_price, 0, ',', '.') }}
                                </span>
                                <span class="bg-amber-400/20 text-amber-400 text-[10px] font-bold px-1 rounded">
                                    @if($item->discount_type === 'percent')
                                        -{{ $item->discount_value }}%
                                    @else
                                        -Rp{{ number_format($item->discount_value / 1000, 0) }}rb
                                    @endif
                                </span>
                            </div>
                            <p class="text-zinc-600 text-[10px] line-through leading-tight">
                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                            </p>
                        </div>

                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
function flashCountdown(endTimestamp) {
    return {
        hours: '00', minutes: '00', seconds: '00',
        expired: false,
        timer: null,
        start() {
            this.tick();
            this.timer = setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = endTimestamp - Math.floor(Date.now() / 1000);
            if (diff <= 0) {
                this.expired = true;
                clearInterval(this.timer);
                return;
            }
            this.hours   = String(Math.floor(diff / 3600)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
            this.seconds = String(diff % 60).padStart(2, '0');
        }
    };
}
</script>
@endpush
@endif

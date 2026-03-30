@php
    $flashSale = \App\Models\FlashSale::active()->with(['items.product'])->first();
@endphp

@if($flashSale)
{{-- Full-width banner, dark themed, countdown + products --}}
<section class="bg-gray-950 border-b border-zinc-900 overflow-hidden">

    {{-- Top bar: label + countdown --}}
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4"
        x-data="flashCountdown({{ $flashSale->ends_at->timestamp }})"
        x-init="start()">

        <div class="flex items-center gap-3">
            {{-- Lightning bolt icon --}}
            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-gray-950" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            <div>
                <p class="text-white text-sm font-semibold">{{ $flashSale->name }}</p>
                <p class="text-zinc-500 text-xs">Penawaran terbatas, jangan sampai terlewat!</p>
            </div>
        </div>

        {{-- Countdown --}}
        <div class="flex items-center gap-2 shrink-0">
            <span class="text-zinc-500 text-xs mr-1">Berakhir dalam:</span>
            <template x-if="!expired">
                <div class="flex items-center gap-1.5">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-center min-w-[44px]">
                        <p class="text-white text-sm font-semibold font-mono" x-text="hours"></p>
                        <p class="text-zinc-600 text-[10px]">Jam</p>
                    </div>
                    <span class="text-zinc-600 font-bold">:</span>
                    <div class="bg-zinc-900 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-center min-w-[44px]">
                        <p class="text-white text-sm font-semibold font-mono" x-text="minutes"></p>
                        <p class="text-zinc-600 text-[10px]">Menit</p>
                    </div>
                    <span class="text-zinc-600 font-bold">:</span>
                    <div class="bg-zinc-900 border border-zinc-800 rounded-lg px-2.5 py-1.5 text-center min-w-[44px]">
                        <p class="text-white text-sm font-semibold font-mono" x-text="seconds"></p>
                        <p class="text-zinc-600 text-[10px]">Detik</p>
                    </div>
                </div>
            </template>
            <template x-if="expired">
                <span class="text-red-400 text-sm font-medium">Flash Sale Berakhir</span>
            </template>
        </div>
    </div>

    {{-- Product strip --}}
    @if($flashSale->items->isNotEmpty())
    <div class="border-t border-zinc-900">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($flashSale->items as $item)
                @if($item->product?->is_active)
                <a href="{{ route('shop.show', $item->product->slug) }}"
                    class="flex-shrink-0 flex items-center gap-3 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl p-3 transition w-56 group">

                    {{-- Product image --}}
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-zinc-800 shrink-0">
                        @if($item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}"
                                alt="{{ $item->product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @endif
                    </div>

                    <div class="min-w-0">
                        <p class="text-white text-xs font-medium truncate">{{ $item->product->name }}</p>
                        <p class="text-amber-400 text-sm font-semibold">
                            Rp {{ number_format($item->flash_price, 0, ',', '.') }}
                        </p>
                        <p class="text-zinc-500 text-xs line-through">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Discount badge --}}
                    <div class="ml-auto shrink-0">
                        <span class="bg-amber-400 text-gray-950 text-[10px] font-bold px-1.5 py-0.5 rounded-md">
                            @if($item->discount_type === 'percent')
                                -{{ $item->discount_value }}%
                            @else
                                -Rp{{ number_format($item->discount_value/1000, 0) }}rb
                            @endif
                        </span>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

</section>

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
            const now = Math.floor(Date.now() / 1000);
            const diff = endTimestamp - now;
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

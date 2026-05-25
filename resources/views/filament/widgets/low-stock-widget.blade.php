<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Stok Menipis & Habis</x-slot>

        @if($products->isEmpty())
            <div style="display:flex;align-items:center;gap:0.5rem;padding:0.25rem 0;color:#a7adb9; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2rem;height:2rem;color:#22c55e;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span style="font-size:0.875rem;font-weight:500;">Semua stok aman</span>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem;">
                @foreach($products as $product)
                    @php
                        $stock    = (int) $product->effective_stock;
                        $maxStock = 10;
                        $pct      = $maxStock > 0 ? min(($stock / $maxStock) * 100, 100) : 0;

                        if ($stock === 0) {
                            $barColor  = '#ef4444';
                            $bgColor   = 'rgba(239,68,68,0.08)';
                            $badgeBg   = 'rgba(239,68,68,0.12)';
                            $badgeText = '#ef4444';
                            $label     = 'Habis';
                        } elseif ($stock <= 3) {
                            $barColor  = '#f97316';
                            $bgColor   = 'rgba(249,115,22,0.08)';
                            $badgeBg   = 'rgba(249,115,22,0.12)';
                            $badgeText = '#f97316';
                            $label     = 'Kritis';
                        } elseif ($stock <= 7) {
                            $barColor  = '#eab308';
                            $bgColor   = 'rgba(234,179,8,0.08)';
                            $badgeBg   = 'rgba(234,179,8,0.12)';
                            $badgeText = '#ca8a04';
                            $label     = 'Menipis';
                        } else {
                            $barColor  = '#6366f1';
                            $bgColor   = 'rgba(99,102,241,0.08)';
                            $badgeBg   = 'rgba(99,102,241,0.12)';
                            $badgeText = '#6366f1';
                            $label     = 'Rendah';
                        }
                    @endphp
                    <div style="background:{{ $bgColor }};border-radius:0.625rem;padding:0.75rem 0.875rem;display:flex;flex-direction:column;gap:0.5rem;">
                        {{-- Header: name + badge --}}
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;">
                            <span style="font-size:0.75rem;font-weight:600;color:inherit;line-height:1.3;flex:1;">
                                {{ \Illuminate\Support\Str::limit($product->name, 28) }}
                            </span>
                            <span style="font-size:0.625rem;font-weight:700;background:{{ $badgeBg }};color:{{ $badgeText }};padding:0.15rem 0.45rem;border-radius:9999px;white-space:nowrap;">
                                {{ $label }}
                            </span>
                        </div>

                        {{-- Progress bar --}}
                        <div style="background:rgba(0,0,0,0.08);border-radius:9999px;height:4px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $barColor }};border-radius:9999px;transition:width 0.3s ease;"></div>
                        </div>

                        {{-- Footer: category + stock count --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:0.6875rem;color:#9ca3af;">
                                {{ $product->category?->name ?? '—' }}
                            </span>
                            <span style="font-size:0.6875rem;font-weight:700;color:{{ $badgeText }};">
                                {{ $stock }} sisa
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

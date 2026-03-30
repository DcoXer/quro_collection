<x-app-layout>
    <div class="max-w-xl mx-auto px-4 py-8 md:py-12">

        <a href="{{ url()->previous(route('home')) }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <div class="mb-6">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Notifikasi</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900">Semua Notifikasi</h1>
        </div>

        @if($notifications->isEmpty())
            <div class="text-center py-16">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400">Belum ada notifikasi</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($notifications as $notif)
                    <a href="{{ route('notifications.read', $notif->id) }}"
                        class="flex items-start gap-3 p-4 rounded-2xl border transition
                            {{ $notif->read_at ? 'border-gray-100 bg-white' : 'border-gray-200 bg-gray-50' }}
                            hover:border-gray-300 hover:shadow-sm">

                        {{-- Icon --}}
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0
                            {{ $notif->read_at ? 'bg-gray-100' : 'bg-gray-900' }}">
                            <svg class="w-4 h-4 {{ $notif->read_at ? 'text-gray-400' : 'text-white' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notif->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>

                        @if(!$notif->read_at)
                            <div class="w-2 h-2 bg-gray-900 rounded-full mt-1.5 shrink-0"></div>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

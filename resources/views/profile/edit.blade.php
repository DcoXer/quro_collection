<x-app-layout>

@push('seo')
<title>Profil Saya — Quro Collection</title>
@endpush

<div class="max-w-4xl mx-auto px-4 py-8 md:py-12">

    <div class="mb-8">
        <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">{{ auth()->user()->name }}</p>
        <h1 style="font-family: 'Playfair Display', serif;"
            class="text-3xl font-semibold text-gray-900">My Profile</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Sidebar --}}
        <div class="md:col-span-1">
            <div class="border border-gray-100 rounded-2xl p-6 text-center sticky top-24">

                {{-- Avatar --}}
                <div class="w-20 h-20 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-white text-2xl font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>

                <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>

                <div class="border-t border-gray-100 mt-4 pt-4 space-y-1 text-left">
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesanan Saya
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Keranjang
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="md:col-span-2 space-y-4">

            {{-- Update Profile --}}
            <div class="border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Informasi Pribadi</h2>
                        <p class="text-xs text-gray-400">Update nama dan email akun kamu</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Nomor HP/WA</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                            Simpan Perubahan
                        </button>
                        @if(session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tersimpan!
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Update Password --}}
            <div class="border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Ubah Password</h2>
                        <p class="text-xs text-gray-400">Gunakan password yang kuat dan unik</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                        @error('current_password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                        @error('password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition bg-gray-50 focus:bg-white">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                            Update Password
                        </button>
                        @if(session('status') === 'password-updated')
                            <p class="text-sm text-green-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Password diperbarui!
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Delete Account --}}
            <div class="border border-red-50 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-red-500">Hapus Akun</h2>
                        <p class="text-xs text-gray-400">Tindakan ini tidak bisa dibatalkan</p>
                    </div>
                </div>

                <button type="button"
                    onclick="showConfirm(
                        'Hapus Akun',
                        'Akun kamu akan dihapus permanen beserta semua data. Tindakan ini tidak bisa dibatalkan.',
                        () => document.getElementById('delete-account-form').submit()
                    )"
                    class="border border-red-200 text-red-400 px-6 py-2.5 rounded-xl text-sm hover:bg-red-50 hover:border-red-300 transition">
                    Hapus Akun Saya
                </button>

                <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="password" value="">
                </form>
            </div>

        </div>
    </div>
</div>

</x-app-layout>
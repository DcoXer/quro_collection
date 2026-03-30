{{-- Confirm Modal --}}
<div id="confirm-modal"
    class="hidden fixed inset-0 bg-black/40 z-[998] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl">
        <h3 style="font-family: 'Playfair Display', serif;"
            class="text-lg font-semibold text-gray-900 mb-2" id="confirm-title">Konfirmasi</h3>
        <p class="text-sm text-gray-500 mb-6" id="confirm-message"></p>
        <div class="flex gap-3">
            <button onclick="closeConfirm()"
                class="flex-1 border border-gray-200 py-2.5 rounded-xl text-sm hover:border-gray-900 transition">
                Batal
            </button>
            <button id="confirm-ok"
                class="flex-1 bg-gray-900 text-white py-2.5 rounded-xl text-sm hover:bg-gray-700 transition">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>
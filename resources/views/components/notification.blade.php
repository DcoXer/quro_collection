{{-- Toast Notification --}}
<div id="toast"
    class="fixed top-6 right-6 z-[999] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium
    bg-white border border-gray-100 text-gray-900 translate-x-[200%] transition-transform duration-300 max-w-xs">
    <span id="toast-icon" class="text-lg leading-none"></span>
    <span id="toast-message"></span>
    <button onclick="hideToast()" class="ml-2 text-gray-300 hover:text-gray-500 leading-none text-base">✕</button>
</div>
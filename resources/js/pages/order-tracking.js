async function loadTracking() {
    const section = document.getElementById('tracking-section');
    if (!section) return;

    const url = section.dataset.url;

    document.getElementById('tracking-loading').classList.remove('hidden');
    document.getElementById('tracking-result').classList.add('hidden');
    document.getElementById('tracking-error').classList.add('hidden');

    try {
        const res  = await fetch(url);
        const data = await res.json();

        document.getElementById('tracking-loading').classList.add('hidden');

        if (data.status === 'error' || !data.data) {
            document.getElementById('tracking-error').classList.remove('hidden');
            return;
        }

        const { summary, history, receiver } = data.data;
        let html = '';

        html += `
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-1">Status</p>
                    <p class="text-sm font-medium text-gray-900">${summary.status ?? '-'}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-1">Kurir</p>
                    <p class="text-sm font-medium text-gray-900 uppercase">${summary.courier ?? '-'}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-1">Pengirim</p>
                    <p class="text-sm font-medium text-gray-900">${summary.shipper ?? '-'}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-1">Penerima</p>
                    <p class="text-sm font-medium text-gray-900">${receiver ?? '-'}</p>
                </div>
            </div>
        `;

        if (history?.length > 0) {
            html += `<p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Riwayat Pengiriman</p>`;
            html += `<div class="space-y-3">`;
            history.forEach((h, i) => {
                html += `
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full mt-1 shrink-0 ${i === 0 ? 'bg-gray-900' : 'bg-gray-200'}"></div>
                            ${i < history.length - 1 ? '<div class="w-px flex-1 bg-gray-100 mt-1"></div>' : ''}
                        </div>
                        <div class="pb-3">
                            <p class="text-xs text-gray-400">${h.date ?? ''} ${h.time ?? ''}</p>
                            <p class="text-sm text-gray-900 mt-0.5">${h.desc ?? ''}</p>
                            ${h.location ? `<p class="text-xs text-gray-400 mt-0.5">${h.location}</p>` : ''}
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
        }

        document.getElementById('tracking-result').innerHTML = html;
        document.getElementById('tracking-result').classList.remove('hidden');

    } catch {
        document.getElementById('tracking-loading').classList.add('hidden');
        document.getElementById('tracking-error').classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', loadTracking);

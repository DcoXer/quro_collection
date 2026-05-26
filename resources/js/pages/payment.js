document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('pay-button');
    if (!btn) return;

    btn.onclick = function () {
        snap.pay(btn.dataset.token, {
            onSuccess: async function () {
                // Panggil checkPayment dulu biar status langsung update,
                // tidak perlu nunggu webhook dari Midtrans
                try {
                    await fetch(btn.dataset.checkUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                } catch (e) {
                    // Tetap redirect meski gagal — webhook akan jalan belakangan
                }
                window.location.href = btn.dataset.successUrl;
            },
            onPending: function () {
                window.location.href = btn.dataset.pendingUrl;
            },
            onError: function () {
                window.showToast?.('Pembayaran gagal, silakan coba lagi.', 'error');
            },
            onClose: function () {
                window.showToast?.('Kamu menutup popup pembayaran.', 'info');
            },
        });
    };
});

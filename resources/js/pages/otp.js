// OTP Input — auto-advance, backspace nav, paste support
const boxes  = document.querySelectorAll('.otp-box');
const hidden = document.getElementById('otp-hidden');

boxes.forEach((box, i) => {
    box.addEventListener('input', (e) => {
        const val = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = val;
        if (val && i < boxes.length - 1) boxes[i + 1].focus();
        updateHidden();
    });

    box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
    });

    box.addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
        paste.split('').forEach((char, j) => {
            if (boxes[i + j]) boxes[i + j].value = char;
        });
        updateHidden();
        boxes[Math.min(i + paste.length, boxes.length - 1)].focus();
    });
});

function updateHidden() {
    hidden.value = Array.from(boxes).map(b => b.value).join('');
}

// Countdown timer — seconds read from data-seconds attribute
const countdown = document.getElementById('countdown');
let seconds     = parseInt(countdown?.dataset.seconds ?? 300, 10);

const timer = setInterval(() => {
    seconds--;
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    countdown.textContent = `${m}:${s.toString().padStart(2, '0')}`;

    if (seconds <= 0) {
        clearInterval(timer);
        countdown.textContent = 'Kadaluarsa';
        countdown.classList.add('text-red-400');
    }
}, 1000);

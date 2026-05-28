<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_user_id')) {
            $fallback = session('otp_purpose') === 'reset_password'
                ? 'password.request'
                : 'register';
            return redirect()->route($fallback);
        }

        if (!session('otp_started_at')) {
            session(['otp_started_at' => now()->timestamp]);
        }

        $secondsLeft = max(0, 300 - (now()->timestamp - session('otp_started_at')));
        $purpose     = session('otp_purpose', 'register');

        return view('auth.otp', compact('secondsLeft', 'purpose'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId  = session('otp_user_id');
        $purpose = session('otp_purpose', 'register');

        if (!$userId) {
            $fallback = $purpose === 'reset_password' ? 'password.request' : 'register';
            return redirect()->route($fallback)
                ->with('error', 'Sesi habis. Silakan coba lagi.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register');
        }

        if (now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.']);
        }

        if ($user->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Hapus OTP dari DB setelah berhasil digunakan
        $user->update([
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        session()->forget(['otp_user_id', 'otp_purpose', 'otp_started_at']);

        // --- Reset password flow ---
        if ($purpose === 'reset_password') {
            session(['password_reset_user_id' => $user->id]);

            return redirect()->route('password.reset')
                ->with('success', 'OTP terverifikasi. Silakan buat password baru.');
        }

        // --- Register / login flow ---
        $user->update([
            'is_verified'       => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('shop.index')
            ->with('success', 'Akun berhasil diverifikasi! Selamat berbelanja.');
    }

    public function resend()
    {
        session(['otp_started_at' => now()->timestamp]);

        $userId  = session('otp_user_id');
        $purpose = session('otp_purpose', 'register');

        if (!$userId) {
            $fallback = $purpose === 'reset_password' ? 'password.request' : 'register';
            return redirect()->route($fallback);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route($purpose === 'reset_password' ? 'password.request' : 'register');
        }

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpVerification($otp, $user->name));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
    }
}

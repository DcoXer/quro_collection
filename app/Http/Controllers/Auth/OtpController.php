<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('register');
        }

        if (!session('otp_started_at')) {
            session(['otp_started_at' => now()->timestamp]);
        }

        $secondsLeft = max(0, 300 - (now()->timestamp - session('otp_started_at')));

        return view('auth.otp', compact('secondsLeft'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        $otp = trim($request->otp);

        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Sesi habis. Silakan daftar ulang.');
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

        // Verifikasi user
        $user->update([
            'is_verified'      => true,
            'otp_code'         => null,
            'otp_expires_at'   => null,
            'email_verified_at' => now(),
        ]);

        session()->forget('otp_user_id');

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('shop.index')
            ->with('success', 'Akun berhasil diverifikasi! Selamat berbelanja.');
    }

    public function resend()
    {
        session(['otp_started_at' => now()->timestamp]);
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register');
        }

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpVerification($otp, $user->name));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Cek apakah user sudah verifikasi OTP
        $user = auth()->user();

        if (!$user->is_verified) {
            // Generate OTP baru
            $otp = (string) random_int(100000, 999999);

            $user->update([
                'otp_code'       => $otp,
                'otp_expires_at' => now()->addMinutes(5),
            ]);

            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\OtpVerification($otp, $user->name));

            auth()->logout();

            session(['otp_user_id' => $user->id]);
            session(['otp_started_at' => now()->timestamp]);

            return redirect()->route('otp.show')
                ->with('info', 'Akun belum diverifikasi. Kode OTP baru telah dikirim.');
        }

        $request->session()->regenerate();

        return redirect()->route('shop.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Selalu tampilkan pesan yang sama — cegah email enumeration
        if (!$user) {
            return back()->with('status', 'Jika email terdaftar, kode OTP akan segera dikirimkan.');
        }

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        session([
            'otp_user_id'    => $user->id,
            'otp_purpose'    => 'reset_password',
            'otp_started_at' => now()->timestamp,
        ]);

        Mail::to($user->email)->send(new PasswordResetOtp($otp, $user->name));

        return redirect()->route('otp.show');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Email udah ada, link ke Google
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    // Buat user baru
                    $user = User::create([
                        'name'               => $googleUser->getName(),
                        'email'              => $googleUser->getEmail(),
                        'google_id'          => $googleUser->getId(),
                        'password'           => null,
                        'email_verified_at'  => now(),
                        'is_verified'        => true,
                    ]);
                    $user->assignRole('user');
                }
            }

            // Pastikan Google user selalu terverifikasi
            if (!$user->email_verified_at) {
                $user->update(['email_verified_at' => now(), 'is_verified' => true]);
            }

            Auth::login($user);

            return redirect()->route('shop.index');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (!session('password_reset_user_id')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = session('password_reset_user_id');

        if (!$userId) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.request');
        }

        $user->forceFill([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        session()->forget('password_reset_user_id');

        Auth::login($user);

        return redirect()->route('shop.index')
            ->with('success', 'Password berhasil diperbarui! Selamat berbelanja.');
    }
}

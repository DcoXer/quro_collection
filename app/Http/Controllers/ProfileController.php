<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user         = $request->user();
        $emailChanged = $user->email !== $request->email;

        $user->update([
            'name'               => $request->name,
            'email'              => $request->email,
            'phone'              => $request->phone,
            'province_id'        => $request->province_id,
            'province_name'      => $request->province_name,
            'city_id'            => $request->city_id,
            'city_name'          => $request->city_name,
            'district_id'        => $request->district_id,
            'district_name'      => $request->district_name,
            'village_id'         => $request->village_id,
            'village_name'       => $request->village_name,
            'address_detail'     => $request->address_detail,
            'email_verified_at'  => $emailChanged ? null : $user->email_verified_at,
            'is_verified'        => $emailChanged ? false : $user->is_verified,
        ]);

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

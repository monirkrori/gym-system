<?php

namespace App\Http\Controllers\dashboard;

use function view;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\dashboard\ProfileUpdateRequest;

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
        $user = $request->user();


        // if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {

        //     // Use the helper to upload the image
        //     $path = ImageHelper::uploadProfilePhoto($request->file('profile_photo'), $user->profile_photo);

        //     // Set the new path to the profile_photo field
        //     $user->profile_photo = $path;
        // }

        $user->fill($request->validated());

        // If email was changed, reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save user data
        $user->save();

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
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

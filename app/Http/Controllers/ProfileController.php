<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil
     */
    public function edit(Request $request): View
    {
        return view('profil.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update data profil
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Upload avatar baru jika ada
        if ($request->hasFile('avatar')) {
            $user->avatar = $this->uploadAvatar($request, $user);
        }

        // Update field lain
        $user->fill($request->validated());

        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Upload avatar
     */
    protected function uploadAvatar(ProfileUpdateRequest $request, User $user): string
    {
        // Hapus avatar lama
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $filename = 'avatar-' . $user->id . '-' . time() . '.' .
            $request->file('avatar')->extension();

        return $request->file('avatar')
            ->storeAs('avatars', $filename, 'public');
    }

    /**
     * Hapus avatar
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Hapus akun
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

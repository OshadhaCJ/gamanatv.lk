<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $this->configureGoogleDriver();

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $this->configureGoogleDriver();

        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->fill([
                'google_id' => $googleUser->getId(),
                'image' => $user->image ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'image' => $googleUser->getAvatar(),
                'role' => 'client',
                'password' => Str::password(32),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Pull the Google OAuth credentials from the `settings` table (not
     * .env) and feed them into Socialite for this request.
     */
    private function configureGoogleDriver(): void
    {
        Config::set('services.google', [
            'client_id' => Setting::get('google_client_id'),
            'client_secret' => Setting::get('google_client_secret'),
            'redirect' => Setting::get('google_redirect_uri', route('google.callback')),
        ]);
    }
}

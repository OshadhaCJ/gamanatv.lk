<?php

namespace App\Http\Controllers;

use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * How long a verification code stays valid.
     */
    private const OTP_TTL_MINUTES = 10;

    /**
     * Minimum gap between two codes being sent to the same phone.
     */
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;

    /**
     * How long a "remembered" login session lasts.
     */
    private const REMEMBER_DAYS = 30;

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showVerify(Request $request)
    {
        $phone = $request->session()->get('otp_phone');

        if (! $phone) {
            return redirect()->route('register');
        }

        return view('auth.verify', ['phone' => $phone]);
    }

    /**
     * Step 1 of registration: collect details and send an SMS code.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $existingOtp = PhoneOtp::where('phone', $validated['phone'])->first();

        if ($existingOtp && $existingOtp->last_sent_at->diffInSeconds(now()) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            throw ValidationException::withMessages([
                'phone' => 'Please wait a moment before requesting another code.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        PhoneOtp::updateOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'client',
                'code' => $code,
                'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
                'last_sent_at' => now(),
            ]
        );

        app(OtpGateway::class)->send($validated['phone'], $code);

        $request->session()->put('otp_phone', $validated['phone']);

        return redirect()->route('verify');
    }

    /**
     * Resend a fresh code to the phone currently pending verification.
     */
    public function resendOtp(Request $request)
    {
        $phone = $request->session()->get('otp_phone');
        $otp = $phone ? PhoneOtp::where('phone', $phone)->first() : null;

        if (! $otp) {
            return redirect()->route('register');
        }

        if ($otp->last_sent_at->diffInSeconds(now()) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            throw ValidationException::withMessages([
                'code' => 'Please wait a moment before requesting another code.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        $otp->update([
            'code' => $code,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'last_sent_at' => now(),
        ]);

        app(OtpGateway::class)->send($otp->phone, $code);

        return back()->with('status', 'A new code has been sent.');
    }

    /**
     * Step 2 of registration: verify the SMS code and create the account.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->session()->get('otp_phone');
        $otp = $phone ? PhoneOtp::where('phone', $phone)->first() : null;

        if (! $otp) {
            return redirect()->route('register');
        }

        if ($otp->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => 'This code has expired. Please request a new one.',
            ]);
        }

        if (! hash_equals($otp->code, $request->input('code'))) {
            throw ValidationException::withMessages([
                'code' => 'The code you entered is incorrect.',
            ]);
        }

        $user = User::create([
            'name' => $otp->name,
            'email' => $otp->email,
            'phone' => $otp->phone,
            'password' => $otp->password,
            'role' => $otp->role,
            'phone_verified_at' => now(),
        ]);

        $otp->delete();
        $request->session()->forget('otp_phone');

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Email + password login. Sessions always persist for 30 days.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, true)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FAQRCode;

class TwoFactorController extends Controller
{
    public function show2FASetup()
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        // Generate secret key jika belum ada
        if (!$user->google2fa_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        } else {
            $secret = $user->google2fa_secret;
        }

        // Generate QR Code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.2fa-setup', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $secret
        ]);
    }

    public function verify2FASetup(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        // Verifikasi kode
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard')->with('success', '2FA berhasil diaktifkan! Sekarang akun Anda lebih aman.');
        }

        return back()->withErrors(['code' => 'Kode OTP tidak valid. Pastikan Anda memasukkan kode yang benar dari aplikasi authenticator.']);
    }

    public function show2FAVerify()
    {
        return view('auth.2fa-verify');
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('dashboard'))->with('success', 'Verifikasi 2FA berhasil!');
        }

        return back()->withErrors(['code' => 'Kode OTP tidak valid.']);
    }

    public function disable2FA()
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();
        session()->forget('2fa_verified');

        return redirect()->route('dashboard')->with('success', '2FA berhasil dinonaktifkan.');
    }

    public function reset2FA()
    {
        $user = Auth::user();

        // Generate new secret key
        $google2fa = app('pragmarx.google2fa');
        $newSecret = $google2fa->generateSecretKey();

        // Update user dengan secret baru
        $user->google2fa_secret = $newSecret;
        $user->save();

        // Clear 2FA session (wajib!)
        session()->forget('2fa_verified');

        // Redirect ke setup dengan pesan success
        return redirect()->route('2fa.setup')->with([
            'success' => '2FA berhasil direset! Scan QR code baru dengan aplikasi authenticator Anda.'
        ]);
    }
}

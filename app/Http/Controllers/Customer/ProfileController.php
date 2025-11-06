<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = auth()->user();
        return view('customer.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the password change form
     */
    public function password()
    {
        return view('customer.profile.password');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('customer.profile.password')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Show the 2FA settings page
     */
    public function twoFactor()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        // Generate secret if not exists or not enabled
        if (!$user->two_factor_secret || !$user->two_factor_enabled) {
            $secret = $google2fa->generateSecretKey();
            // Save the secret temporarily so it's consistent between page loads
            if (!$user->two_factor_enabled) {
                $user->update(['two_factor_secret' => encrypt($secret)]);
            }
        } else {
            $secret = decrypt($user->two_factor_secret);
        }

        // Generate QR code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('customer.profile.two-factor', compact('user', 'secret', 'qrCodeUrl'));
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        // Get the secret that was displayed to the user
        if (!$user->two_factor_secret) {
            return back()->withErrors(['code' => 'Please refresh the page and try again.']);
        }

        $secret = decrypt($user->two_factor_secret);

        // Verify the code with a window of 2 (allows for time drift)
        $valid = $google2fa->verifyKey($secret, $request->code, 2);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code. Please make sure your device time is correct.']);
        }

        // Generate recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        // Save 2FA settings
        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        return redirect()->route('customer.profile.two-factor')
            ->with('success', 'Two-factor authentication enabled successfully!')
            ->with('recovery_codes', $recoveryCodes);
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('customer.profile.two-factor')
            ->with('success', 'Two-factor authentication disabled successfully!');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = auth()->user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('customer.profile.two-factor')
                ->with('error', 'Two-factor authentication is not enabled.');
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        return view('customer.profile.recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('customer.profile.two-factor')
                ->with('error', 'Two-factor authentication is not enabled.');
        }

        // Generate new recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

        return redirect()->route('customer.profile.recovery-codes')
            ->with('success', 'Recovery codes regenerated successfully!')
            ->with('recovery_codes', $recoveryCodes);
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\Hash;

class TwoFactorService
{
    public const CODE_TTL_MINUTES = 10;

    public const RESEND_SECONDS = 60;

    public const MAX_ATTEMPTS = 5;

    public const RECOVERY_CODES_COUNT = 10;

    private const RECOVERY_ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function sendCode(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
            'two_factor_sent_at' => now(),
            'two_factor_attempts' => 0,
        ])->save();

        $user->notify(new TwoFactorCodeNotification($code, self::CODE_TTL_MINUTES));
    }

    public function resendIn(User $user): int
    {
        if (!$user->two_factor_sent_at) {
            return 0;
        }

        $elapsed = (int) abs(now()->diffInSeconds($user->two_factor_sent_at));

        return max(0, self::RESEND_SECONDS - $elapsed);
    }

    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->two_factor_code || !$user->two_factor_expires_at) {
            return false;
        }

        if ($user->two_factor_expires_at->isPast()) {
            return false;
        }

        if ($user->two_factor_attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (!Hash::check($code, $user->two_factor_code)) {
            $user->increment('two_factor_attempts');

            return false;
        }

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'two_factor_attempts' => 0,
            'two_factor_confirmed_at' => $user->two_factor_confirmed_at ?? now(),
        ])->save();

        return true;
    }

    public function generateRecoveryCodes(User $user): array
    {
        $user->twoFactorRecoveryCodes()->delete();

        $codes = [];

        for ($i = 0; $i < self::RECOVERY_CODES_COUNT; $i++) {
            $code = $this->randomRecoveryCode();

            $codes[] = $this->formatRecoveryCode($code);

            $user->twoFactorRecoveryCodes()->create([
                'code' => $this->hashRecoveryCode($code),
            ]);
        }

        return $codes;
    }

    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $normalized = $this->normalizeRecoveryCode($code);

        if (strlen($normalized) !== 8) {
            return false;
        }

        $hashed = $this->hashRecoveryCode($normalized);

        $recoveryCode = $user->twoFactorRecoveryCodes()
            ->whereNull('used_at')
            ->get()
            ->first(fn ($item) => hash_equals($item->code, $hashed));

        if (!$recoveryCode) {
            return false;
        }

        $recoveryCode->forceFill(['used_at' => now()])->save();

        return true;
    }

    public function remainingRecoveryCodes(User $user): int
    {
        return $user->twoFactorRecoveryCodes()->whereNull('used_at')->count();
    }

    private function randomRecoveryCode(): string
    {
        $code = '';
        $max = strlen(self::RECOVERY_ALPHABET) - 1;

        for ($i = 0; $i < 8; $i++) {
            $code .= self::RECOVERY_ALPHABET[random_int(0, $max)];
        }

        return $code;
    }

    private function hashRecoveryCode(string $code): string
    {
        return hash('sha256', $code);
    }

    private function normalizeRecoveryCode(string $code): string
    {
        return preg_replace('/[^A-Z0-9]/', '', strtoupper($code));
    }

    private function formatRecoveryCode(string $code): string
    {
        return substr($code, 0, 4) . '-' . substr($code, 4, 4);
    }
}

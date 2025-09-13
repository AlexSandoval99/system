<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use App\Notifications\TwoFactorCodeMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;

class TwoFactorService
{
    public int $ttlMinutes = 10;

    public function generateFor($user, $ip=null, $ua=null): TwoFactorCode
    {
        // Eliminar códigos previos sin usar
        TwoFactorCode::where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->delete();

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $tfc = TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes($this->ttlMinutes),
            'ip'         => $ip,
            'user_agent' => $ua,
        ]);

        // Envío inmediato al Gmail configurado en .env
        $user->notify(new TwoFactorCodeMail($code, $this->ttlMinutes));

        return $tfc;
    }

    public function verify($user, string $code): bool
    {
        $record = TwoFactorCode::where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->latest()->first();

        if (!$record || !hash_equals($record->code, $code)) {
            return false;
        }

        $record->update(['consumed_at' => now()]);
        return true;
    }

    public function tooManyAttemptsKey($userId): string
    {
        return "2fa:attempts:user:$userId";
    }

    public function hitAttempt($userId): void
    {
        RateLimiter::hit($this->tooManyAttemptsKey($userId), 60); // ventana 60s
    }

    public function attemptsRemaining($userId): int
    {
        return RateLimiter::remaining($this->tooManyAttemptsKey($userId), 5); // 5 intentos/min
    }

    public function clearAttempts($userId): void
    {
        RateLimiter::clear($this->tooManyAttemptsKey($userId));
    }
}

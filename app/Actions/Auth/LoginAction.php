<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final readonly class LoginAction
{
    /**
     * @return array{user: User, token: string}
     */
    public function execute(LoginRequest $request): array
    {
        $email = $request->validated(key: 'email');
        $password = $request->validated(key: 'password');
        $throttleKey = $this->throttleKey(email: $email, ip: $request->ip());

        $this->ensureIsNotRateLimited(throttleKey: $throttleKey, request: $request);

        $user = User::query()->where(column: 'email', operator: '=', value: $email)->first();
        if ($user === null || ! Hash::check(value: $password, hashedValue: $user->password)) {
            RateLimiter::hit(key: $throttleKey);

            throw ValidationException::withMessages(messages: [
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        RateLimiter::clear(key: $throttleKey);

        $token = $user->createToken(name: 'auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function throttleKey(string $email, string $ip): string
    {
        return Str::of(string: $email)
            ->lower()
            ->append(separator: '|', ip: $ip)
            ->transliterate()
            ->toString();
    }

    private function ensureIsNotRateLimited(string $throttleKey, LoginRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts(key: $throttleKey, maxAttempts: 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn(key: $throttleKey);

        event(new Lockout(request: $request));

        throw ValidationException::withMessages(messages: [
            'email' => __(key: 'auth.throttle', replace: [
                'seconds' => $seconds,
                'minutes' => ceil(num: $seconds / 60),
            ]),
        ]);
    }
}

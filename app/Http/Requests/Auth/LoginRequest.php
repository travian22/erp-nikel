<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = trim($this->input('login') ?? $this->input('email') ?? $this->input('username') ?? '');

        if (empty($loginInput)) {
            throw ValidationException::withMessages([
                'login' => __('Username atau Email wajib diisi.'),
                'email' => __('Username atau Email wajib diisi.'),
            ]);
        }

        $password = (string) $this->input('password');

        // Case-insensitive query by username or email
        $user = User::where(function ($query) use ($loginInput) {
            $query->where('username', $loginInput)
                ->orWhere('email', $loginInput)
                ->orWhereRaw('LOWER(username) = ?', [mb_strtolower($loginInput)])
                ->orWhereRaw('LOWER(email) = ?', [mb_strtolower($loginInput)]);
        })->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
                'email' => trans('auth.failed'),
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'login' => __('Akun Anda tidak aktif. Silakan hubungi Administrator.'),
                'email' => __('Akun Anda tidak aktif. Silakan hubungi Administrator.'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $loginInput = trim($this->input('login') ?? $this->input('email') ?? $this->input('username') ?? '');

        return Str::transliterate(Str::lower($loginInput).'|'.$this->ip());
    }
}

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan use Hash
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Tambahkan use User
use App\Models\Mahasiswa; // Tambahkan use Mahasiswa
use App\Models\Pejabat; // Tambahkan use Pejabat
use App\Models\AdminStaff; // Tambahkan use AdminStaff
use App\Models\AdminAkademik; // Tambahkan use AdminAkademik

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Ganti 'email' menjadi 'identifier'
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->input('identifier');
        $password = $this->input('password');
        $remember = $this->boolean('remember');
        $user = null;

        // 1. Coba cari berdasarkan Email dulu
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        } else {
            // 2. Jika bukan email, coba cari berdasarkan NIM/NIP
            // Cari Mahasiswa berdasarkan NIM
            $mahasiswa = Mahasiswa::where('nim', $identifier)->with('user')->first();
            if ($mahasiswa) {
                $user = $mahasiswa->user;
            } else {
                // Cari Pejabat berdasarkan NIP/NIDN
                $pejabat = Pejabat::where('nip_atau_nidn', $identifier)->with('user')->first();
                if ($pejabat) {
                    $user = $pejabat->user;
                } else {
                    // Cari Staff Jurusan berdasarkan NIP
                    $staff = AdminStaff::where('nip_staff', $identifier)->with('user')->first();
                    if ($staff) {
                        $user = $staff->user;
                    } else {
                        // Cari Admin Akademik berdasarkan NIP
                        $admin = AdminAkademik::where('nip_akademik', $identifier)->with('user')->first();
                        if ($admin) {
                            $user = $admin->user;
                        }
                    }
                }
            }
        }

        // 3. Verifikasi User dan Password
        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                // Arahkan error ke field 'identifier'
                'identifier' => trans('auth.failed'),
            ]);
        }

        // 4. Lakukan Login
        Auth::login($user, $remember);

        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            // Arahkan error ke field 'identifier'
            'identifier' => trans('auth.throttle', [
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
        // Gunakan 'identifier' + IP address
        return Str::transliterate(Str::lower($this->input('identifier')).'|'.$this->ip());
    }
}

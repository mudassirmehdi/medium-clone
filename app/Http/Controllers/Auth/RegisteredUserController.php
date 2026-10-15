<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use League\Uri\Http;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    private function detectCountry(): ?string
    {
        try {
            $ip = request()->ip();

            $isPublicIp = $ip && filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );

            // For local development, detect the country of this machine's
            // public internet connection. In production, look up the visitor IP.
            $url = app()->environment('local') || !$isPublicIp
                ? 'https://ipapi.co/country/'
                : "https://ipapi.co/{$ip}/country/";

            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'MediumClone/1.0',
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::warning('Country API request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $country = strtoupper(trim($response->body()));

            return preg_match('/^[A-Z]{2}$/', $country)
                ? $country
                : null;
        } catch (\Throwable $e) {
            Log::warning('Country detection failed.', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $name = $request->name;

        // Generate a slug from the name
        $username = Str::slug($name);

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $this->detectCountry(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

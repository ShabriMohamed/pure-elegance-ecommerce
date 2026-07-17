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
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Enforces strong password, saves all profile fields, assigns customer role.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone'      => ['nullable', 'string', 'max:20'],
            'password'   => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        // is_admin/is_active are not mass-assignable; new users get the safe DB
        // defaults (is_admin = false, is_active = true).
        $user = User::create([
            'name'       => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'password'   => Hash::make($validated['password']),
        ]);

        // Assign customer role (Spatie)
        if (\Spatie\Permission\Models\Role::where('name', 'customer')->exists()) {
            $user->assignRole('customer');
        }

        event(new Registered($user));

        Auth::login($user);

        Log::info('New user registered', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'ip'      => $request->ip(),
        ]);

        return redirect(route('home', absolute: false))
            ->with('success', 'Welcome to Pure Elegance! Your account has been created.');
    }
}

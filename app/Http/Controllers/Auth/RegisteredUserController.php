<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

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
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->mixedCase(),
            ],
        ]);

        $phone = $request->phone;
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
        ]);

        // --- 2. TETAPKAN ROLE 'USER' ---
        try {
            // Cari role 'User' (Pastikan role 'User' ada di database)
            $userRole = Role::findByName('User'); // Nama role harus persis

            if ($userRole) {
                $user->assignRole($userRole); // Tetapkan role ke user baru
            } else {
                // Catat error jika role tidak ditemukan
                Log::error("Role 'User' not found. Cannot assign to new user ID: " . $user->id);
            }
        } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            // Tangani jika tabel roles belum ada atau role 'User' belum dibuat
            Log::error("Role 'User' does not exist. Please run the seeder. Error: " . $e->getMessage());
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            Log::error("Failed to assign role during registration for user ID: " . $user->id . ". Error: " . $e->getMessage());
        }
        // --- AKHIR PENETAPAN ROLE ---

        // Kirim event Registered
        event(new Registered($user));

        // Login user baru
        Auth::login($user);

        return redirect(route('user.dashboard', absolute: false));
    }
}
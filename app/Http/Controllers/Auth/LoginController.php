<?php

namespace App\Http\Controllers\Auth;

//use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends RoutingController
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request) 
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        // Cek status aktif dulu sebelum cek password, biar pesan errornya jelas
        if ($user && $user->status !== 'aktif') {
            throw ValidationException::withMessages([
                'username' => 'Akun ini nonaktif. Hubungi admin sekolah.',
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        Auth::user()->update(['last_login' => now()]);

        return redirect()->intended($this->redirectPath());
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Tiap role diarahkan ke dashboard masing-masing setelah login
    protected function redirectPath(): string
    {
        return match (Auth::user()->role) {
            'kepala_sekolah' => '/dashboard/kepala-sekolah',
            'admin_tu', 'staff' => '/dashboard/tu',
            'guru' => '/dashboard/guru',
            default => '/dashboard',
        };
    }
}
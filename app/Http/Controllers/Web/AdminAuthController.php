<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller {
    public function showLogin() {
        if (Session::has('admin_id')) {
            return Session::get('admin_role') == 'penyuluh' ? redirect()->route('admin.konsultasi.index') : redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        // In a real app we use Hash::check, but depending on how seeders were made they might be plain text
        // If it fails with Hash::check, we fallback to plain text check for now just in case
        $valid = false;
        if ($user) {
            if (Hash::check($request->password, $user->kata_sandi)) {
                $valid = true;
            } elseif ($request->password === $user->kata_sandi) {
                // Temporary fallback if seeders didn't hash passwords
                $user->kata_sandi = Hash::make($request->password);
                $user->save();
                $valid = true;
            }
        }

        if ($valid) {
            if (in_array($user->peran, ['admin', 'penyuluh'])) {
                Session::put('admin_id', $user->id);
                Session::put('admin_role', $user->peran);
                Session::put('admin_name', $user->nama_lengkap);

                if ($user->peran == 'penyuluh') {
                    return redirect()->route('admin.konsultasi.index');
                }
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses ke panel ini.']);
            }
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
    }

    public function logout() {
        Session::forget(['admin_id', 'admin_role', 'admin_name']);
        return redirect()->route('admin.login');
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    // Role yang boleh lihat log SEMUA user - selain itu cuma lihat log miliknya sendiri
    protected array $roleBolehLihatSemua = ['admin_tu', 'super_admin', 'kepala_sekolah'];

    public function index(Request $request)
    {
        $user = Auth::user();
        $bolehLihatSemua = in_array($user->role, $this->roleBolehLihatSemua);

        $query = LogAktivitas::with('user.pegawai')->latest('created_at');

        if (! $bolehLihatSemua) {
            $query->where('id_user', $user->id_user);
        } elseif ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', $request->aktivitas);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('log-aktivitas.index', [
            'logs' => $logs,
            'bolehLihatSemua' => $bolehLihatSemua,
            'daftarUser' => $bolehLihatSemua ? User::with('pegawai')->get() : collect(),
            'filterUserId' => $request->user_id,
            'filterAktivitas' => $request->aktivitas,
        ]);
    }
}

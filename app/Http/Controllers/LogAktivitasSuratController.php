<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitasSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasSuratController extends Controller
{
    protected array $roleBolehLihatSemua = ['admin_tu', 'super_admin', 'kepala_sekolah'];

    public function index(Request $request)
    {
        $user = Auth::user();
        $bolehLihatSemua = in_array($user->role, $this->roleBolehLihatSemua);

        $query = LogAktivitasSurat::with('user.pegawai')->latest('created_at');

        if (! $bolehLihatSemua) {
            $query->where('id_user', $user->id_user);
        } elseif ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('tipe_surat')) {
            $query->where('tipe_surat', $request->tipe_surat);
        }

        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', $request->aktivitas);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('log-surat.index', [
            'logs' => $logs,
            'bolehLihatSemua' => $bolehLihatSemua,
            'daftarUser' => $bolehLihatSemua ? User::with('pegawai')->get() : collect(),
            'filterUserId' => $request->user_id,
            'filterTipeSurat' => $request->tipe_surat,
            'filterAktivitas' => $request->aktivitas,
        ]);
    }
}
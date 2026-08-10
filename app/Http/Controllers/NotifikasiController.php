<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('id_user', Auth::id())
            ->latest('created_at')->paginate(15);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->id_user === Auth::id(), 403);

        $notifikasi->tandaiDibaca();

        // Arahkan ke surat terkait kalau ada
        if ($notifikasi->tipe_surat === 'keluar') {
            return redirect()->route('surat-keluar.show', $notifikasi->id_surat);
        }
        return redirect()->route('surat-masuk.show', $notifikasi->id_surat);
    }

    public function tandaiSemuaDibaca()
    {
        Notifikasi::where('id_user', Auth::id())->where('sudah_dibaca', false)->update(['sudah_dibaca' => true]);
        return back()->with('sukses', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\KategoriSurat;
use App\Models\KlasifikasiArsip;
use App\Models\LogAktivitas;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::with(['kategori', 'penerima.pegawai'])->latest('tanggal_diterima');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('surat-masuk.index', [
            'suratMasuk' => $query->paginate(15)->withQueryString(),
            'filterStatus' => $request->status,
        ]);
    }

    public function create()
    {
        return view('surat-masuk.create', [
            'kategoriList' => KategoriSurat::orderBy('nama_kategori')->get(),
            'klasifikasiList' => KlasifikasiArsip::orderBy('nama_klasifikasi')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_surat_asal' => 'nullable|string|max:100',
            'asal_instansi' => 'required|string|max:150',
            'id_kategori' => 'required|exists:kategori_surat,id_kategori',
            'id_klasifikasi' => 'required|exists:klasifikasi_arsip,id_klasifikasi',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'tanggal_diterima' => 'required|date',
            'sifat_surat' => 'required|in:biasa,penting,segera,rahasia',
        ]);

        $suratMasuk = SuratMasuk::create([
            ...$data,
            'nomor_surat_masuk' => 'AGD-'.now()->format('Ymd').'-'.str_pad((SuratMasuk::whereDate('created_at', now())->count() + 1), 3, '0', STR_PAD_LEFT),
            'status' => 'baru',
            'diterima_oleh' => Auth::id(),
        ]);

        LogAktivitas::catat('tambah_data', 'Surat Masuk', "Mencatat surat masuk: {$suratMasuk->perihal}");

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('sukses', 'Surat masuk berhasil dicatat.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['kategori', 'klasifikasi', 'penerima.pegawai', 'disposisi.penerimaPegawai', 'disposisi.penerimaUnit', 'disposisi.pemberiDisposisi']);

        return view('surat-masuk.show', compact('suratMasuk'));
    }
}

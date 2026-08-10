@extends('layouts.app')
@section('title', 'Detail Surat Masuk')

@section('content')
<div class="max-w-2xl">
    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-display text-lg" style="color: var(--navy);">{{ $suratMasuk->perihal }}</h2>
                <p class="text-sm mt-1" style="color: var(--ink-muted);">Dari: {{ $suratMasuk->asal_instansi }} &middot; No. Agenda: {{ $suratMasuk->nomor_surat_masuk }}</p>
            </div>
            @php $w = ['baru'=>'#C9972F','didisposisi'=>'#16324F','diproses'=>'#16324F','selesai'=>'#2F6B4F','diarsipkan'=>'#767C86']; @endphp
            <span class="text-xs px-2.5 py-1 rounded" style="background: {{ $w[$suratMasuk->status] }}22; color: {{ $w[$suratMasuk->status] }};">{{ ucfirst($suratMasuk->status) }}</span>
        </div>
        <p class="mt-4 text-sm" style="color: var(--ink-muted);">Diterima {{ $suratMasuk->tanggal_diterima?->format('d M Y') }} oleh {{ $suratMasuk->penerima->pegawai->nama_lengkap ?? $suratMasuk->penerima->username }}</p>
    </div>

    {{-- Form buat disposisi (khusus kepala sekolah) --}}
    @if(auth()->user()->role === 'kepala_sekolah')
        <div class="mt-6 bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-display text-base mb-3" style="color: var(--navy);">Buat Disposisi</h3>
            <form method="POST" action="{{ route('surat-masuk.disposisi.store', $suratMasuk) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Disposisi ke</label>
                        <select name="tujuan_tipe" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                            <option value="pegawai">Pegawai tertentu</option>
                            <option value="unit">Unit kerja</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Tujuan (ID Pegawai/Unit)</label>
                        <input type="number" name="tujuan_id" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" placeholder="Lihat di Master Data" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Instruksi</label>
                    <input type="text" name="instruksi" placeholder="mis. Mohon ditindaklanjuti" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Kirim Disposisi</button>
            </form>
        </div>
    @endif

    {{-- Riwayat disposisi --}}
    @if($suratMasuk->disposisi->isNotEmpty())
        <div class="mt-6">
            <h3 class="font-display text-base mb-3" style="color: var(--navy);">Riwayat Disposisi</h3>
            <div class="space-y-3">
                @foreach($suratMasuk->disposisi as $d)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between text-sm">
                        <div>
                            <p>Ke: {{ $d->tujuan_label }} @if($d->instruksi)&middot; {{ $d->instruksi }}@endif</p>
                            <p class="text-xs mt-1" style="color: var(--ink-muted);">Dari {{ $d->pemberiDisposisi->nama_lengkap ?? '-' }} &middot; {{ $d->tanggal_disposisi?->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @php $ws = ['menunggu'=>'#C9972F','diterima'=>'#16324F','ditindaklanjuti'=>'#16324F','diteruskan'=>'#767C86','selesai'=>'#2F6B4F']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded" style="background: {{ $ws[$d->status] }}22; color: {{ $ws[$d->status] }};">{{ ucfirst($d->status) }}</span>
                            @if($d->status !== 'selesai')
                                <form method="POST" action="{{ route('disposisi.selesaikan', $d) }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium" style="color: var(--navy);">Tandai Selesai</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

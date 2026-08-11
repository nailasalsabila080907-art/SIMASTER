@extends('layouts.app')
@section('title', 'Surat Keluar')

@section('content')
<div class="w-full">
    @if(session('sukses'))<div class="mb-5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm px-5 py-4">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-5 py-4">{{ session('gagal') }}</div>@endif

    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
        <div>
            <h2 class="font-display text-xl" style="color: var(--navy);">Daftar Surat Keluar</h2>
            <p class="text-sm mt-1" style="color: var(--ink-muted);">Kelola draft, pengajuan, approval, dan surat yang sudah terbit.</p>
        </div>
        <a href="{{ route('surat-keluar.create') }}" class="text-sm px-5 py-2.5 rounded-xl text-white font-medium" style="background: var(--navy);">+ Buat Surat</a>
    </div>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['' => 'Semua', 'draft' => 'Draft', 'ditolak' => 'Ditolak', 'diajukan' => 'Diajukan', 'terkirim' => 'Terbit'] as $val => $label)
            <a href="{{ route('surat-keluar.index', $val ? ['status' => $val] : []) }}" class="text-xs px-3.5 py-2 rounded-full border {{ $filterStatus === $val || (!$filterStatus && !$val) ? 'text-white border-transparent' : 'border-gray-300 bg-white' }}" style="{{ $filterStatus === $val || (!$filterStatus && !$val) ? 'background: var(--navy);' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-100 bg-gray-50" style="color: var(--ink-muted);">
                        <th class="px-5 py-4 font-medium">Nomor</th>
                        <th class="px-5 py-4 font-medium">Perihal</th>
                        <th class="px-5 py-4 font-medium">Kategori</th>
                        <th class="px-5 py-4 font-medium">Tanggal</th>
                        <th class="px-5 py-4 font-medium">Pembuat</th>
                        <th class="px-5 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratKeluar as $s)
                        @php $warna = ['draft'=>'#767C86','diajukan'=>'#C9972F','ditolak'=>'#B0432E','terkirim'=>'#16324F','disetujui'=>'#2F6B4F','diarsipkan'=>'#767C86']; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-mono text-xs">{{ $s->nomor_surat ?? '—' }}</td>
                            <td class="px-5 py-4"><a href="{{ route('surat-keluar.show', $s) }}" class="font-medium hover:underline">{{ $s->perihal }}</a></td>
                            <td class="px-5 py-4" style="color: var(--ink-muted);">{{ $s->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-5 py-4" style="color: var(--ink-muted);">{{ $s->tanggal_surat?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-5 py-4" style="color: var(--ink-muted);">{{ $s->pembuat->pegawai->nama_lengkap ?? $s->pembuat->username ?? '-' }}</td>
                            <td class="px-5 py-4"><span class="text-xs px-2.5 py-1 rounded-full" style="background: {{ $warna[$s->status] ?? '#767C86' }}22; color: {{ $warna[$s->status] ?? '#767C86' }};">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center" style="color: var(--ink-muted);">Belum ada surat keluar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $suratKeluar->links() }}</div>
</div>
@endsection

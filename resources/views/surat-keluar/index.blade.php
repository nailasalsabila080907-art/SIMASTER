@extends('layouts.app')
@section('title', 'Surat Keluar')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            @foreach(['' => 'Semua', 'draft' => 'Draft', 'diajukan' => 'Diajukan', 'terkirim' => 'Terbit'] as $val => $label)
                <a href="{{ route('surat-keluar.index', $val ? ['status' => $val] : []) }}"
                   class="text-xs px-3 py-1.5 rounded-full {{ $filterStatus === $val || (!$filterStatus && !$val) ? 'text-white' : 'border border-gray-300' }}"
                   style="{{ $filterStatus === $val || (!$filterStatus && !$val) ? 'background: var(--navy);' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('surat-keluar.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Buat Surat</a>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">Perihal</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Dibuat oleh</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suratKeluar as $s)
                    <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location='{{ route('surat-keluar.show', $s) }}'">
                        <td class="px-5 py-3">{{ $s->perihal }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $s->kategori->nama_kategori }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $s->pembuat->pegawai->nama_lengkap ?? $s->pembuat->username }}</td>
                        <td class="px-5 py-3">
                            @php $warna = ['draft'=>'#767C86','diajukan'=>'#C9972F','disetujui'=>'#2F6B4F','ditolak'=>'#B0432E','terkirim'=>'#16324F','diarsipkan'=>'#767C86']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded" style="background: {{ $warna[$s->status] }}22; color: {{ $warna[$s->status] }};">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada surat keluar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $suratKeluar->links() }}</div>
</div>
@endsection

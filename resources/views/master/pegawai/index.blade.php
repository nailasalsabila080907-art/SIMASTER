@extends('layouts.app')
@section('title', 'Master Pegawai')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm" style="color: var(--ink-muted);">Kelola data pegawai dan akun login.</p>
        <a href="{{ route('pegawai.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Tambah Pegawai</a>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">NIP</th>
                    <th class="px-5 py-3 font-medium">Jabatan</th>
                    <th class="px-5 py-3 font-medium">Unit</th>
                    <th class="px-5 py-3 font-medium">Akun</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pegawai as $p)
                    <tr>
                        <td class="px-5 py-3">{{ $p->nama_lengkap }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $p->nip }}</td>
                        <td class="px-5 py-3">{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
                        <td class="px-5 py-3">{{ $p->unitKerja->nama_unit ?? '-' }}</td>
                        <td class="px-5 py-3">
                            @if($p->user)
                                <span class="text-xs px-2 py-0.5 rounded" style="background: #E9F3EC; color: #2F6B4F;">{{ $p->user->username }}</span>
                            @else
                                <span class="text-xs" style="color: var(--ink-muted);">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('pegawai.edit', $p) }}" class="text-xs font-medium" style="color: var(--navy);">Ubah</a>
                            <form action="{{ route('pegawai.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus pegawai ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada data pegawai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $pegawai->links() }}</div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Master Jabatan')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm" style="color: var(--ink-muted);">Kelola daftar jabatan dan level approval berjenjang.</p>
        <a href="{{ route('jabatan.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Tambah Jabatan</a>
    </div>

    @if(session('sukses'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>
    @endif
    @if(session('gagal'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">Nama Jabatan</th>
                    <th class="px-5 py-3 font-medium">Level</th>
                    <th class="px-5 py-3 font-medium">Keterangan</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jabatan as $j)
                    <tr>
                        <td class="px-5 py-3">{{ $j->nama_jabatan }}</td>
                        <td class="px-5 py-3">{{ $j->level_jabatan }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $j->keterangan ?? '-' }}</td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('jabatan.edit', $j) }}" class="text-xs font-medium" style="color: var(--navy);">Ubah</a>
                            <form action="{{ route('jabatan.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus jabatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada data jabatan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $jabatan->links() }}</div>
</div>
@endsection

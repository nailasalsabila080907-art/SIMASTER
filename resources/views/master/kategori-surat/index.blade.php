@extends('layouts.app')
@section('title', 'Kategori Surat')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm" style="color: var(--ink-muted);">Kelola jenis-jenis surat yang tersedia di sistem.</p>
        <a href="{{ route('kategori-surat.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Tambah Kategori</a>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">Nama Kategori</th>
                    <th class="px-5 py-3 font-medium">Jenis</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($kategori as $k)
                    <tr>
                        <td class="px-5 py-3">{{ $k->nama_kategori }}</td>
                        <td class="px-5 py-3"><span class="text-xs px-2 py-0.5 rounded" style="background: #EAF0F6; color: var(--navy);">{{ ucfirst($k->jenis) }}</span></td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('kategori-surat.edit', $k) }}" class="text-xs font-medium" style="color: var(--navy);">Ubah</a>
                            <form action="{{ route('kategori-surat.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada kategori surat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $kategori->links() }}</div>
</div>
@endsection

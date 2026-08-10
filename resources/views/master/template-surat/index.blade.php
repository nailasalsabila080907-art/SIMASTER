@extends('layouts.app')
@section('title', 'Template Surat')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm" style="color: var(--ink-muted);">Kelola template surat dan field dinamisnya.</p>
        <a href="{{ route('template-surat.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Tambah Template</a>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">Nama Template</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Kode</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($template as $t)
                    <tr>
                        <td class="px-5 py-3">{{ $t->nama_template }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $t->kategori->nama_kategori }}</td>
                        <td class="px-5 py-3 font-mono text-xs">{{ $t->kode_template }}</td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('template-surat.edit', $t) }}" class="text-xs font-medium" style="color: var(--navy);">Kelola</a>
                            <form action="{{ route('template-surat.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada template.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $template->links() }}</div>
</div>
@endsection

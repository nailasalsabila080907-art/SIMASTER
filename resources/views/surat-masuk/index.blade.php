@extends('layouts.app')
@section('title', 'Surat Masuk')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            @foreach(['' => 'Semua', 'baru' => 'Baru', 'didisposisi' => 'Didisposisi', 'selesai' => 'Selesai'] as $val => $label)
                <a href="{{ route('surat-masuk.index', $val ? ['status' => $val] : []) }}"
                   class="text-xs px-3 py-1.5 rounded-full {{ $filterStatus === $val || (!$filterStatus && !$val) ? 'text-white' : 'border border-gray-300' }}"
                   style="{{ $filterStatus === $val || (!$filterStatus && !$val) ? 'background: var(--navy);' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('surat-masuk.create') }}" class="text-sm px-4 py-2 rounded-lg text-white" style="background: var(--navy);">+ Catat Surat Masuk</a>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    <th class="px-5 py-3 font-medium">No. Agenda</th>
                    <th class="px-5 py-3 font-medium">Asal Instansi</th>
                    <th class="px-5 py-3 font-medium">Perihal</th>
                    <th class="px-5 py-3 font-medium">Diterima</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suratMasuk as $s)
                    <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location='{{ route('surat-masuk.show', $s) }}'">
                        <td class="px-5 py-3 font-mono text-xs">{{ $s->nomor_surat_masuk }}</td>
                        <td class="px-5 py-3">{{ $s->asal_instansi }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $s->perihal }}</td>
                        <td class="px-5 py-3" style="color: var(--ink-muted);">{{ $s->tanggal_diterima?->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            @php $w = ['baru'=>'#C9972F','didisposisi'=>'#16324F','diproses'=>'#16324F','selesai'=>'#2F6B4F','diarsipkan'=>'#767C86']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded" style="background: {{ $w[$s->status] }}22; color: {{ $w[$s->status] }};">{{ ucfirst($s->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center" style="color: var(--ink-muted);">Belum ada surat masuk tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $suratMasuk->links() }}</div>
</div>
@endsection

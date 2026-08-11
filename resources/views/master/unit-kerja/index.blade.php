@extends('layouts.app')
@section('title','Unit Kerja')
@section('content')
<div class="w-full">
    @if(session('sukses'))<div class="mb-5 rounded-xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 text-sm">{{session('sukses')}}</div>@endif
    @if(session('gagal'))<div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">{{session('gagal')}}</div>@endif
    <div class="flex justify-between items-center mb-5"><div><h2 class="font-display text-xl" style="color:var(--navy)">Unit Kerja</h2><p class="text-sm mt-1" style="color:var(--ink-muted)">Kelola unit yang digunakan pada penomoran dan data pegawai.</p></div><a href="{{route('unit-kerja.create')}}" class="px-5 py-2.5 rounded-xl text-white text-sm" style="background:var(--navy)">+ Tambah Unit</a></div>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left"><th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama Unit</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($unitKerja as $u)<tr><td class="px-5 py-4 font-mono">{{$u->kode_unit}}</td><td class="px-5 py-4">{{$u->nama_unit}}</td><td class="px-5 py-4">{{$u->status}}</td><td class="px-5 py-4 text-right"><a href="{{route('unit-kerja.edit',$u)}}" class="text-xs mr-3" style="color:var(--navy)">Ubah</a><form class="inline" method="POST" action="{{route('unit-kerja.destroy',$u)}}" onsubmit="return confirm('Hapus unit ini?')">@csrf @method('DELETE')<button class="text-xs text-red-600">Hapus</button></form></td></tr>@empty<tr><td colspan="4" class="px-5 py-10 text-center" style="color:var(--ink-muted)">Belum ada unit kerja.</td></tr>@endforelse</tbody></table></div></div><div class="mt-5">{{$unitKerja->links()}}</div>
</div>
@endsection

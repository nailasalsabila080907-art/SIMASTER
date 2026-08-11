@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm" style="color:var(--ink-muted)">Selamat datang kembali,</p>
            <h2 class="font-display text-2xl mt-1" style="color:var(--navy)">{{ $user->pegawai->nama_lengkap ?? $user->username }}</h2>
            <p class="text-xs mt-1" style="color:var(--ink-muted)">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        @if($approvalSaya > 0)<a href="{{route('approval.index')}}" class="rounded-xl px-4 py-2.5 text-sm text-white" style="background:var(--gold)">{{ $approvalSaya }} approval menunggu</a>@endif
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">
        @foreach($statistik as $item)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center justify-between"><span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm" style="background:#EAF0F6;color:var(--navy)">{{ $item['icon'] }}</span><span class="text-[10px] uppercase tracking-wider" style="color:var(--ink-muted)">SIMASTER</span></div>
                <p class="mt-5 text-3xl font-semibold font-display" style="color:var(--navy)">{{ $item['nilai'] }}</p>
                <p class="mt-1 text-xs" style="color:var(--ink-muted)">{{ $item['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,.8fr)] gap-5">
        <section class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between"><div><h3 class="font-display text-lg" style="color:var(--navy)">Aktivitas Surat</h3><p class="text-xs mt-1" style="color:var(--ink-muted)">Perbandingan surat masuk dan surat keluar 6 bulan terakhir.</p></div></div>
            <div class="mt-7 space-y-5">
                @php $maxGrafik=max(1,$grafik->flatMap(fn($x)=>[$x['masuk'],$x['keluar']])->max()); @endphp
                @foreach($grafik as $g)
                    <div class="grid grid-cols-[55px_minmax(0,1fr)] gap-3 items-center"><span class="text-xs font-medium">{{$g['label']}}</span><div class="space-y-2"><div class="flex items-center gap-2"><div class="h-2 rounded-full" style="width:{{($g['masuk']/$maxGrafik)*100}}%;min-width:3px;background:#6EA8D7"></div><span class="text-[11px] w-8" style="color:var(--ink-muted)">{{$g['masuk']}}</span></div><div class="flex items-center gap-2"><div class="h-2 rounded-full" style="width:{{($g['keluar']/$maxGrafik)*100}}%;min-width:3px;background:var(--green)"></div><span class="text-[11px] w-8" style="color:var(--ink-muted)">{{$g['keluar']}}</span></div></div></div>
                @endforeach
            </div>
            <div class="mt-5 flex gap-5 text-xs" style="color:var(--ink-muted)"><span><i class="inline-block w-2 h-2 rounded-full mr-1" style="background:#6EA8D7"></i> Surat Masuk</span><span><i class="inline-block w-2 h-2 rounded-full mr-1" style="background:var(--green)"></i> Surat Keluar</span></div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start justify-between"><div><h3 class="font-display text-lg" style="color:var(--navy)">Notifikasi Terbaru</h3><p class="text-xs mt-1" style="color:var(--ink-muted)">{{ $notifikasiBelumDibaca }} belum dibaca</p></div><a href="{{route('notifikasi.index')}}" class="text-xs" style="color:var(--navy)">Lihat semua</a></div>
            <div class="mt-5 divide-y divide-gray-100">
                @forelse($notifikasiTerbaru as $n)<a href="{{route('notifikasi.tandai-dibaca',$n)}}" onclick="event.preventDefault();document.getElementById('notif-{{$n->id_notifikasi}}').submit();" class="block py-3"><div class="flex gap-3"><span class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center" style="background:{{$n->sudah_dibaca?'#F1F2F4':'#FBF3E1'}};color:var(--gold)">•</span><div class="min-w-0"><p class="text-sm font-medium truncate">{{$n->judul}}</p><p class="text-xs mt-1 line-clamp-2" style="color:var(--ink-muted)">{{$n->pesan}}</p></div></div></a><form id="notif-{{$n->id_notifikasi}}" method="POST" action="{{route('notifikasi.tandai-dibaca',$n)}}" class="hidden">@csrf</form>@empty<div class="py-8 text-center text-sm" style="color:var(--ink-muted)">Tidak ada notifikasi.</div>@endforelse
            </div>
        </section>
    </div>

    <div class="grid xl:grid-cols-[minmax(0,1.2fr)_minmax(0,.8fr)] gap-5">
        <section class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between"><h3 class="font-display text-lg" style="color:var(--navy)">Aktivitas Terbaru</h3><a href="{{route('log-aktivitas.index')}}" class="text-xs" style="color:var(--navy)">Lihat semua</a></div>
            <div class="mt-4 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left border-b border-gray-100" style="color:var(--ink-muted)"><th class="py-3 pr-4">Waktu</th><th class="py-3 pr-4">Aktivitas</th><th class="py-3">Modul</th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($aktivitasTerbaru as $log)<tr><td class="py-3 pr-4 text-xs whitespace-nowrap" style="color:var(--ink-muted)">{{$log->created_at->diffForHumans()}}</td><td class="py-3 pr-4">{{$log->deskripsi ?? $log->aktivitas}}</td><td class="py-3 text-xs" style="color:var(--ink-muted)">{{$log->modul ?? '-'}}</td></tr>@empty<tr><td colspan="3" class="py-8 text-center" style="color:var(--ink-muted)">Belum ada aktivitas.</td></tr>@endforelse</tbody></table></div>
        </section>
        <section class="bg-white rounded-2xl border border-gray-200 p-6">
            <h3 class="font-display text-lg" style="color:var(--navy)">Ringkasan Kategori</h3><p class="text-xs mt-1" style="color:var(--ink-muted)">Jumlah surat keluar berdasarkan kategori.</p>
            <div class="mt-5 space-y-4">@php $maxKategori=max(1,$ringkasanKategori->max('jumlah') ?? 0); @endphp @forelse($ringkasanKategori as $r)<div><div class="flex justify-between text-xs mb-1.5"><span>{{$r['nama']}}</span><strong>{{$r['jumlah']}}</strong></div><div class="h-2 rounded-full bg-gray-100 overflow-hidden"><div class="h-full rounded-full" style="width:{{($r['jumlah']/$maxKategori)*100}}%;background:var(--navy)"></div></div></div>@empty<p class="text-sm" style="color:var(--ink-muted)">Belum ada data surat.</p>@endforelse</div>
        </section>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIMASTER</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        :root{--navy-deep:#0F2540;--navy:#16324F;--gold:#C9972F;--gold-light:#E4C878;--paper:#F4F5F7;--ink:#23262B;--ink-muted:#767C86;--green:#2F6B4F;--red:#B0432E}
        html,body{min-height:100%;background:var(--paper)}
        body{font-family:'Inter',sans-serif;color:var(--ink)}
        .font-display{font-family:'Lora',serif}
        .sidebar-link{display:flex;align-items:center;gap:.65rem;padding:.65rem .85rem;border-radius:9px;font-size:.875rem;color:rgba(255,255,255,.68);transition:.15s}
        .sidebar-link:hover{background:rgba(255,255,255,.07);color:#fff}
        .sidebar-link.active{background:rgba(201,151,47,.16);color:var(--gold-light);font-weight:600}
        .sidebar-link.disabled{opacity:.35;pointer-events:none}
        .scrollbar::-webkit-scrollbar{width:7px;height:7px}.scrollbar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:999px}
        @media(max-width:1023px){.sidebar-shell{width:76px}.sidebar-text,.sidebar-label{display:none}.sidebar-brand{justify-content:center;padding-left:.5rem;padding-right:.5rem}.sidebar-link{justify-content:center}.sidebar-link span,.sidebar-link .nav-count{display:none}}
    </style>
    @stack('head')
</head>
<body class="min-h-screen">
<div class="min-h-screen flex">
    <aside class="sidebar-shell w-64 shrink-0 flex flex-col" style="background:var(--navy-deep)">
        <div class="sidebar-brand px-5 py-5 flex items-center gap-3 border-b border-white/10">
            <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center shrink-0 overflow-hidden bg-white" style="border-color:var(--gold-light)">
                @php
                    $logoCandidates = ['images/logo-smkn7.jpeg.jpg', 'images/logo-smkn7.jpeg.jpg', 'images/logo-smkn7.jpeg.jpeg', 'images/logo-smkn7.webp'];
                    $logoPath = collect($logoCandidates)->first(fn($path) => file_exists(public_path($path)));
                @endphp
                @if($logoPath)
                    <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7" class="w-full h-full object-contain">
                @else
                    <span class="font-display text-sm" style="color:var(--gold-light)">S7</span>
                @endif
            </div>
            <div class="leading-tight sidebar-text"><p class="text-white text-sm font-semibold">SMK Negeri 7</p><p class="text-white/50 text-xs">SIMASTER</p></div>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto scrollbar space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span>Dashboard</span></a>

            <p class="sidebar-label px-3 pt-5 pb-1 text-[10px] uppercase tracking-[.16em] text-white/35">Persuratan</p>
            <a href="{{ route('surat-masuk.index') }}" class="sidebar-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}"><span>Surat Masuk</span></a>
            <a href="{{ route('surat-keluar.index') }}" class="sidebar-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}"><span>Surat Keluar</span></a>
            @if(in_array(auth()->user()->role,['admin_tu','super_admin','kepala_sekolah']))
                <a href="{{ route('approval.index') }}" class="sidebar-link {{ request()->routeIs('approval.*') ? 'active' : '' }}"><span>Approval Surat</span></a>
            @endif
            <a href="{{ route('arsip.index') }}" class="sidebar-link {{ request()->routeIs('arsip.*') ? 'active' : '' }}"><span>Arsip Surat</span></a>

            @if(in_array(auth()->user()->role,['admin_tu','super_admin']))
                <p class="sidebar-label px-3 pt-5 pb-1 text-[10px] uppercase tracking-[.16em] text-white/35">Master Data</p>
                <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}"><span>Pegawai</span></a>
                <a href="{{ route('pengguna.index') }}" class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"><span>Pengguna</span></a>
                <a href="{{ route('unit-kerja.index') }}" class="sidebar-link {{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}"><span>Unit Kerja</span></a>
                <a href="{{ route('jurusan.index') }}" class="sidebar-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}"><span>Jurusan</span></a>
                <a href="{{ route('jabatan.index') }}" class="sidebar-link {{ request()->routeIs('jabatan.*') ? 'active' : '' }}"><span>Jabatan</span></a>
                <a href="{{ route('kategori-surat.index') }}" class="sidebar-link {{ request()->routeIs('kategori-surat.*') ? 'active' : '' }}"><span>Kategori Surat</span></a>
                <a href="{{ route('klasifikasi-arsip.index') }}" class="sidebar-link {{ request()->routeIs('klasifikasi-arsip.*') ? 'active' : '' }}"><span>Klasifikasi Arsip</span></a>
                <a href="{{ route('template-surat.index') }}" class="sidebar-link {{ request()->routeIs('template-surat.*') ? 'active' : '' }}"><span>Template Surat</span></a>
            @endif

            <p class="sidebar-label px-3 pt-5 pb-1 text-[10px] uppercase tracking-[.16em] text-white/35">Sistem</p>
            <a href="{{ route('notifikasi.index') }}" class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                <span>Notifikasi</span>
                @php $jmlNotif=\App\Models\Notifikasi::where('id_user',auth()->id())->where('sudah_dibaca',false)->count(); @endphp
                @if($jmlNotif>0)<span class="nav-count ml-auto text-[10px] px-1.5 py-0.5 rounded-full text-white" style="background:var(--gold)">{{ $jmlNotif }}</span>@endif
            </a>
            <a href="{{ route('log-aktivitas.index') }}" class="sidebar-link {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}"><span>Log Aktivitas</span></a>
            @if(in_array(auth()->user()->role,['admin_tu','super_admin']))
                <a href="{{ route('sekolah.edit') }}" class="sidebar-link {{ request()->routeIs('sekolah.*') ? 'active' : '' }}"><span>Profil Sekolah</span></a>
            @endif
        </nav>

        <div class="px-3 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-link w-full text-left"><span>Keluar</span></button></form>
        </div>
    </aside>

    <div class="flex-1 min-w-0 flex flex-col">
        <header class="bg-white border-b border-gray-200 px-6 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-20">
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Sistem Informasi Manajemen</p><h1 class="font-display text-xl" style="color:var(--navy)">@yield('title','Dashboard')</h1></div>
            <a href="{{ route('profil.index') }}" class="flex items-center gap-3 hover:opacity-75 transition">
    <div class="w-10 h-10 rounded-full overflow-hidden border-2 shrink-0" style="border-color:var(--gold-light);background:#f3f4f6">
        @if(auth()->user()->pegawai && auth()->user()->pegawai->foto_path)
            <img
                src="{{ asset('storage/' . auth()->user()->pegawai->foto_path) }}"
                alt="Foto Profil"
                class="w-full h-full object-cover"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-sm font-semibold" style="color:var(--navy)">
                {{ strtoupper(substr(auth()->user()->pegawai->nama_lengkap ?? auth()->user()->username, 0, 1)) }}
            </div>
        @endif
    </div>

    <div class="text-right leading-tight">
        <p class="text-sm font-semibold">
            {{ auth()->user()->pegawai->nama_lengkap ?? auth()->user()->username }}
        </p>
        <p class="text-xs" style="color:var(--ink-muted)">
            {{ ucwords(str_replace('_',' ',auth()->user()->role)) }}
        </p>
    </div>
</a>
        </header>

        <main class="flex-1 p-5 lg:p-7 xl:p-8 w-full">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Sistem Persuratan Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        :root {
            --navy-deep: #0F2540;
            --navy: #16324F;
            --gold: #C9972F;
            --gold-light: #E4C878;
            --paper: #F4F5F7;
            --ink: #23262B;
            --ink-muted: #767C86;
        }
        body { font-family: 'Inter', sans-serif; background: var(--paper); color: var(--ink); }
        .font-display { font-family: 'Lora', serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.9rem; border-radius: 8px; font-size: 0.875rem;
            color: rgba(255,255,255,0.65); transition: background 0.15s ease, color 0.15s ease;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .sidebar-link.active { background: rgba(201,151,47,0.15); color: var(--gold-light); font-weight: 500; }
        .sidebar-link.disabled { opacity: 0.35; pointer-events: none; }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="w-64 shrink-0 flex flex-col" style="background: var(--navy-deep);">
            <div class="px-5 py-5 flex items-center gap-3 border-b border-white/10">
                <div class="w-9 h-9 rounded-full border-2 flex items-center justify-center shrink-0" style="border-color: var(--gold-light);">
                    <span class="font-display text-sm" style="color: var(--gold-light);">S7</span>
                </div>
                <div class="leading-tight">
                    <p class="text-white text-sm font-medium">SMK Negeri 7</p>
                    <p class="text-white/50 text-xs">Persuratan Sekolah</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-white/35">Surat</p>
                <a href="{{ route('surat-keluar.index') }}" class="sidebar-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">Surat Keluar</a>
                <a href="{{ route('surat-masuk.index') }}" class="sidebar-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">Surat Masuk</a>

                @if(auth()->user()->role === 'kepala_sekolah')
                    <a href="{{ route('approval.index') }}" class="sidebar-link {{ request()->routeIs('approval.*') ? 'active' : '' }}">Approval Surat</a>
                @endif

                @if(in_array(auth()->user()->role, ['admin_tu', 'super_admin']))
                    <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-white/35">Master Data</p>
                    <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">Pegawai</a>
                    <a href="{{ route('jabatan.index') }}" class="sidebar-link {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">Jabatan</a>
                    <a href="{{ route('template-surat.index') }}" class="sidebar-link {{ request()->routeIs('template-surat.*') ? 'active' : '' }}">Template Surat</a>
                    <a href="{{ route('kategori-surat.index') }}" class="sidebar-link {{ request()->routeIs('kategori-surat.*') ? 'active' : '' }}">Kategori Surat</a>
                @endif

                <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-white/35">Lainnya</p>
                <a href="{{ route('notifikasi.index') }}" class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                    Notifikasi
                    @php $jmlNotif = \App\Models\Notifikasi::where('id_user', auth()->id())->where('sudah_dibaca', false)->count(); @endphp
                    @if($jmlNotif > 0)
                        <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full text-white" style="background: var(--gold);">{{ $jmlNotif }}</span>
                    @endif
                </a>
                <a href="#" class="sidebar-link disabled">Arsip</a>
                <a href="{{ route('log-aktivitas.index') }}" class="sidebar-link {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}">
                    Log Aktivitas
                </a>
                @if(in_array(auth()->user()->role, ['admin_tu', 'super_admin']))
                    <a href="{{ route('sekolah.edit') }}" class="sidebar-link {{ request()->routeIs('sekolah.*') ? 'active' : '' }}">Profil Sekolah</a>
                @endif
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-full text-left">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Konten utama --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
                <h1 class="font-display text-lg" style="color: var(--navy);">@yield('title', 'Dashboard')</h1>
                <div class="text-right leading-tight">
                    <p class="text-sm font-medium">{{ auth()->user()->pegawai->nama_lengkap ?? auth()->user()->username }}</p>
                    <p class="text-xs" style="color: var(--ink-muted);">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
            </header>

            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

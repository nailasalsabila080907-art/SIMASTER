<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIMASTER</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root{
            --bs-primary:#178754;
            --primary-rgb:23,135,84;
            --primary-dark:#126841;
            --primary-light:#E6F5EC;
            --sidebar-w:260px;
            --topbar-h:68px;
            --ink:#1E1F26;
            --ink-muted:#8B8D97;
            --surface:#FAFBFA;
            --border:#EBEFEC;
            --success:#178754;
            --warning:#F0A202;
            --danger:#E5484D;
            --info:#0EA5A4;
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            font-family:'Public Sans',system-ui,-apple-system,sans-serif;
            background:#F6FAF7;
            color:var(--ink);
            font-size:.9rem;
        }
        h1,h2,h3,h4,h5,h6{font-weight:700;letter-spacing:-.01em}
        a{text-decoration:none}

        /* ===== Sidebar ===== */
        .sidebar{
            position:fixed;top:0;bottom:0;left:0;width:var(--sidebar-w);
            background:#fff;border-right:1px solid var(--border);
            display:flex;flex-direction:column;z-index:1030;
            transition:transform .2s ease;
        }
        .sidebar-brand{
            height:var(--topbar-h);display:flex;align-items:center;gap:.7rem;
            padding:0 1.35rem;border-bottom:1px solid var(--border);flex-shrink:0;
            background:linear-gradient(135deg,#E6F5EC,#FFFFFF 70%);
        }
        .sidebar-brand .logo-badge{
            width:38px;height:38px;border-radius:11px;overflow:hidden;flex-shrink:0;
            background:#fff;border:1px solid var(--border);
            display:flex;align-items:center;justify-content:center;
        }
        .sidebar-brand .logo-badge img{width:100%;height:100%;object-fit:contain}
        .sidebar-brand .logo-badge span{color:var(--bs-primary);font-weight:800;font-size:.85rem}
        .sidebar-brand .brand-text p{margin:0;line-height:1.2}
        .sidebar-brand .brand-title{font-weight:800;font-size:.9rem;color:var(--ink)}
        .sidebar-brand .brand-sub{font-size:.7rem;color:var(--ink-muted);letter-spacing:.04em}

        .sidebar-scroll{flex:1;overflow-y:auto;padding:1.1rem .85rem}
        .sidebar-scroll::-webkit-scrollbar{width:5px}
        .sidebar-scroll::-webkit-scrollbar-thumb{background:var(--border);border-radius:99px}

        .nav-section-label{
            font-size:.68rem;text-transform:uppercase;letter-spacing:.09em;
            color:var(--ink-muted);font-weight:700;padding:1rem .65rem .4rem;
        }
        .nav-link-custom{
            display:flex;align-items:center;gap:.7rem;padding:.6rem .7rem;
            border-radius:10px;color:#5B5D6B;font-size:.855rem;font-weight:500;
            margin-bottom:.15rem;position:relative;
        }
        .nav-link-custom i{font-size:1.05rem;width:20px;text-align:center;color:#A6A8B4}
        .nav-link-custom:hover{background:var(--surface);color:var(--ink)}
        .nav-link-custom.active{background:var(--primary-light);color:var(--bs-primary);font-weight:700}
        .nav-link-custom.active i{color:var(--bs-primary)}
        .nav-badge{
            margin-left:auto;background:var(--bs-primary);color:#fff;font-size:.68rem;
            font-weight:700;border-radius:99px;padding:.1rem .45rem;
        }

        .sidebar-footer{padding:.85rem;border-top:1px solid var(--border)}
        .btn-logout{
            display:flex;align-items:center;gap:.7rem;width:100%;border:none;background:transparent;
            padding:.6rem .7rem;border-radius:10px;color:#C4463F;font-weight:600;font-size:.855rem;
        }
        .btn-logout:hover{background:#FCEBEA}

        /* ===== Main / Topbar ===== */
        .main-wrap{margin-left:var(--sidebar-w);min-height:100vh;display:flex;flex-direction:column}
        .topbar{
            height:var(--topbar-h);
            background:#fff;
            border-bottom:2px solid var(--primary-light);
            display:flex;align-items:center;justify-content:space-between;
            padding:0 1.75rem;position:sticky;top:0;z-index:1020;
        }
        .topbar .page-eyebrow{font-size:.68rem;text-transform:uppercase;letter-spacing:.08em;color:var(--ink-muted);margin:0}
        .topbar .page-title{font-size:1.15rem;margin:0;color:var(--ink)}
        .btn-icon-ghost{
            width:38px;height:38px;border-radius:10px;border:1px solid var(--border);
            display:inline-flex;align-items:center;justify-content:center;color:#5B5D6B;background:#fff;position:relative;
        }
        .btn-icon-ghost:hover{background:var(--surface)}
        .notif-dot{
            position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--danger);
            border-radius:50%;border:2px solid #fff;
        }
        .user-chip{display:flex;align-items:center;gap:.65rem;padding:.3rem .5rem .3rem .3rem;border-radius:12px}
        .user-chip:hover{background:var(--surface)}
        .user-chip .avatar{width:38px;height:38px;border-radius:10px;overflow:hidden;background:var(--primary-light);flex-shrink:0}
        .user-chip .avatar img{width:100%;height:100%;object-fit:cover}
        .user-chip .avatar .fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--bs-primary);font-size:.85rem}
        .user-chip .u-name{font-size:.83rem;font-weight:700;color:var(--ink);line-height:1.2;margin:0}
        .user-chip .u-role{font-size:.7rem;color:var(--ink-muted);margin:0}

        .content{flex:1;padding:1.75rem}

        /* ===== Shared card look ===== */
        .card{border:1px solid var(--border);border-radius:14px;box-shadow:0 1px 2px rgba(16,24,40,.03)}
        .card-header{background:transparent;border-bottom:1px solid var(--border);padding:1.1rem 1.35rem}
        .card-body{padding:1.35rem}

        /* mobile */
        .sidebar-toggle{display:none}
        @media (max-width:991.98px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.show{transform:translateX(0)}
            .main-wrap{margin-left:0}
            .sidebar-toggle{display:inline-flex}
            .sidebar-backdrop{display:none;position:fixed;inset:0;background:rgba(15,17,26,.4);z-index:1025}
            .sidebar-backdrop.show{display:block}
        }
    </style>
    @stack('head')
</head>
<body>

<aside class="sidebar" id="appSidebar">
    <div class="sidebar-brand">
        <div class="logo-badge">
            @php
                $logoCandidates = ['images/logo-smkn7.jpeg.jpg', 'images/logo-smkn7.jpeg.jpeg', 'images/logo-smkn7.webp'];
                $logoPath = collect($logoCandidates)->first(fn($path) => file_exists(public_path($path)));
            @endphp
            @if($logoPath)
                <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7">
            @else
                <span>S7</span>
            @endif
        </div>
        <div class="brand-text">
            <p class="brand-title">SMK Negeri 7</p>
            <p class="brand-sub">SIMASTER</p>
        </div>
    </div>

    <div class="sidebar-scroll">
        <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <p class="nav-section-label">Persuratan</p>
        <a href="{{ route('surat-masuk.index') }}" class="nav-link-custom {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
            <i class="bi bi-envelope-arrow-down"></i> Surat Masuk
        </a>
        <a href="{{ route('surat-keluar.index') }}" class="nav-link-custom {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Surat Keluar
        </a>
        @if(in_array(auth()->user()->role,['admin_tu','super_admin','kepala_sekolah']))
            <a href="{{ route('approval.index') }}" class="nav-link-custom {{ request()->routeIs('approval.*') ? 'active' : '' }}">
                <i class="bi bi-patch-check"></i> Approval Surat
            </a>
        @endif
        <a href="{{ route('arsip.index') }}" class="nav-link-custom {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
            <i class="bi bi-archive"></i> Arsip Surat
        </a>

        @if(in_array(auth()->user()->role,['admin_tu','super_admin']))
            <p class="nav-section-label">Master Data</p>
            <a href="{{ route('pegawai.index') }}" class="nav-link-custom {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pegawai
            </a>
            <a href="{{ route('pengguna.index') }}" class="nav-link-custom {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Pengguna
            </a>
            <a href="{{ route('unit-kerja.index') }}" class="nav-link-custom {{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i> Unit Kerja
            </a>
            <a href="{{ route('jurusan.index') }}" class="nav-link-custom {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard"></i> Jurusan
            </a>
            <a href="{{ route('jabatan.index') }}" class="nav-link-custom {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase"></i> Jabatan
            </a>
            <a href="{{ route('kategori-surat.index') }}" class="nav-link-custom {{ request()->routeIs('kategori-surat.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori Surat
            </a>
            <a href="{{ route('klasifikasi-arsip.index') }}" class="nav-link-custom {{ request()->routeIs('klasifikasi-arsip.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> Klasifikasi Arsip
            </a>
            <a href="{{ route('template-surat.index') }}" class="nav-link-custom {{ request()->routeIs('template-surat.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-richtext"></i> Template Surat
            </a>
        @endif

        <p class="nav-section-label">Sistem</p>
        <a href="{{ route('notifikasi.index') }}" class="nav-link-custom {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
            @php $jmlNotif=\App\Models\Notifikasi::where('id_user',auth()->id())->where('sudah_dibaca',false)->count(); @endphp
            @if($jmlNotif>0)<span class="nav-badge">{{ $jmlNotif }}</span>@endif
        </a>
        <a href="{{ route('log-aktivitas.index') }}" class="nav-link-custom {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Log Aktivitas
        </a>
        @if(in_array(auth()->user()->role,['admin_tu','super_admin']))
            <a href="{{ route('sekolah.edit') }}" class="nav-link-custom {{ request()->routeIs('sekolah.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Profil Sekolah
            </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</aside>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div class="main-wrap">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-icon-ghost sidebar-toggle" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <p class="page-eyebrow">Sistem Informasi Manajemen</p>
                <h1 class="page-title">@yield('title','Dashboard')</h1>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('notifikasi.index') }}" class="btn-icon-ghost">
                <i class="bi bi-bell fs-6"></i>
                @if(($jmlNotif ?? 0) > 0)<span class="notif-dot"></span>@endif
            </a>

            <a href="{{ route('profil.index') }}" class="user-chip">
                <div class="avatar">
                    @if(auth()->user()->pegawai && auth()->user()->pegawai->foto_path)
                        <img src="{{ asset('storage/' . auth()->user()->pegawai->foto_path) }}" alt="Foto Profil">
                    @else
                        <div class="fallback">{{ strtoupper(substr(auth()->user()->pegawai->nama_lengkap ?? auth()->user()->username, 0, 1)) }}</div>
                    @endif
                </div>
                <div class="d-none d-sm-block">
                    <p class="u-name">{{ auth()->user()->pegawai->nama_lengkap ?? auth()->user()->username }}</p>
                    <p class="u-role">{{ ucwords(str_replace('_',' ',auth()->user()->role)) }}</p>
                </div>
            </a>
        </div>
    </header>

    <main class="content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggle = document.getElementById('sidebarToggle');
    function closeSidebar(){ sidebar.classList.remove('show'); backdrop.classList.remove('show'); }
    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('show');
    });
    backdrop?.addEventListener('click', closeSidebar);
</script>
@stack('scripts')

</body>
</html>
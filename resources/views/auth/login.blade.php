<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIMASTER SMK Negeri 7 Pekanbaru</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root{
            --bs-primary:#178754;
            --primary-dark:#126841;
            --primary-light:#E6F5EC;
            --ink:#1E1F26;
            --ink-muted:#8B8D97;
            --border:#EBEFEC;
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            font-family:'Public Sans',system-ui,-apple-system,sans-serif;
            color:var(--ink);
            font-size:.9rem;
        }
        h1,h2,h3,h4,h5,h6{font-weight:700;letter-spacing:-.01em}

        .login-wrap{min-height:100vh}

        /* ===== Panel branding ===== */
        .brand-panel{
            position:relative;
            display:flex;flex-direction:column;justify-content:space-between;
            padding:3rem;
            background:
                radial-gradient(circle at 0 0, transparent 22px, rgba(255,255,255,.05) 23px),
                radial-gradient(circle at 40px 40px, transparent 22px, rgba(255,255,255,.05) 23px),
                linear-gradient(160deg, var(--primary-dark), var(--bs-primary) 75%);
            background-size:40px 40px, 40px 40px, cover;
            overflow:hidden;
        }
        .brand-top{position:relative;z-index:1;display:flex;align-items:center;gap:.8rem}
        .brand-logo-circle{
            width:52px;height:52px;border-radius:50%;background:#fff;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;overflow:hidden;
            border:2px solid rgba(255,255,255,.35);
        }
        .brand-logo-circle img{width:78%;height:78%;object-fit:contain}
        .brand-logo-circle span{color:var(--bs-primary);font-weight:800;font-size:1rem}
        .brand-top .school-name{color:rgba(255,255,255,.75);font-size:.78rem;letter-spacing:.14em;text-transform:uppercase}

        .brand-mid{position:relative;z-index:1;max-width:26rem}
        .brand-mid h2{font-size:1.9rem;line-height:1.3;color:#fff;margin-bottom:0}
        .brand-divider{height:2px;width:56px;background:#E4C878;margin:1.5rem 0}
        .brand-mid p{color:rgba(255,255,255,.72);font-size:.9rem;line-height:1.7;margin:0}

        .brand-footer{position:relative;z-index:1;color:rgba(255,255,255,.45);font-size:.75rem}

        /* ===== Panel form ===== */
        .form-panel{display:flex;align-items:center;justify-content:center;padding:2rem;background:#fff}
        .form-inner{width:100%;max-width:23rem}

        .form-logo-mobile{display:none}
        @media (max-width:991.98px){
            .form-logo-mobile{display:flex;align-items:center;gap:.7rem;margin-bottom:2rem}
            .form-logo-mobile .logo-badge{
                width:42px;height:42px;border-radius:12px;overflow:hidden;
                background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;
                border:1px solid var(--border);
            }
            .form-logo-mobile .logo-badge img{width:100%;height:100%;object-fit:contain}
            .form-logo-mobile .logo-badge span{color:var(--bs-primary);font-weight:800;font-size:.85rem}
            .form-logo-mobile .school-name{font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted)}
        }

        .form-title{color:var(--ink);font-size:1.4rem;margin-bottom:.3rem}
        .form-subtitle{color:var(--ink-muted);font-size:.85rem;margin-bottom:1.75rem}

        .form-label-custom{font-size:.83rem;font-weight:600;color:var(--ink);margin-bottom:.4rem}
        .input-field{
            border:1px solid var(--border);border-radius:10px;padding:.65rem .9rem;
            font-size:.87rem;background:#FAFBFA;transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .input-field:focus{
            outline:none;border-color:var(--bs-primary);box-shadow:0 0 0 3px rgba(23,135,84,.12);background:#fff;
        }

        .btn-login{
            background:var(--bs-primary);border:none;border-radius:10px;color:#fff;
            font-weight:700;font-size:.88rem;padding:.7rem;width:100%;transition:background .15s ease;
        }
        .btn-login:hover{background:var(--primary-dark);color:#fff}
        .btn-login:focus-visible, input:focus-visible, a:focus-visible{outline:2px solid var(--bs-primary);outline-offset:2px}

        .form-check-input:checked{background-color:var(--bs-primary);border-color:var(--bs-primary)}
    </style>
</head>
<body>

@php
    $logoCandidates = ['images/logo-smkn7.jpeg.jpg', 'images/logo-smkn7.jpeg.jpeg', 'images/logo-smkn7.webp'];
    $logoPath = collect($logoCandidates)->first(fn($path) => file_exists(public_path($path)));
@endphp

<div class="login-wrap row g-0">

    {{-- Panel branding --}}
    <div class="col-lg-6 d-none d-lg-flex brand-panel">
        <div class="brand-top">
            <div class="brand-logo-circle">
                @if($logoPath)
                    <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7">
                @else
                    <span>S7</span>
                @endif
            </div>
            <span class="school-name">SMK Negeri 7 Pekanbaru</span>
        </div>

        <div class="brand-mid">
            <h2>Sistem Informasi Manajemen Persuratan Sekolah</h2>
            <div class="brand-divider"></div>
            <p>
                Satu pintu untuk surat masuk, surat keluar, approval, dan arsip —
                tata usaha, kurikulum, kesiswaan, dan sarana prasarana dalam satu sistem.
            </p>
        </div>

        <p class="brand-footer">&copy; {{ date('Y') }} SMK Negeri 7 Pekanbaru</p>
    </div>

    {{-- Panel form login --}}
    <div class="col-lg-6 form-panel">
        <div class="form-inner">

            <div class="form-logo-mobile">
                <div class="logo-badge">
                    @if($logoPath)
                        <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7">
                    @else
                        <span>S7</span>
                    @endif
                </div>
                <span class="school-name">SMK Negeri 7 Pekanbaru</span>
            </div>

            <h1 class="form-title">Masuk ke SIMASTER</h1>
            <p class="form-subtitle">Gunakan username dan password akun kepegawaian Anda.</p>

            @if ($errors->any())
                <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.83rem;">
                    <i class="bi bi-exclamation-circle-fill mt-1"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label-custom d-block">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="input-field form-control"
                        placeholder="mis. admin.tu1"
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label-custom d-block">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="input-field form-control"
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:.85rem;color:var(--ink-muted);">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <p class="mt-4 text-center" style="font-size:.78rem;color:var(--ink-muted);">
                Lupa password? Hubungi Admin TU untuk reset akun.
            </p>
        </div>
    </div>
</div>

</body>
</html>
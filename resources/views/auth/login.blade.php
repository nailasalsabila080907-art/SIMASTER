<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Sistem Persuratan Sekolah</title>
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
        .kawung-pattern {
            background-image:
                radial-gradient(circle at 0 0, transparent 22px, var(--navy) 23px),
                radial-gradient(circle at 40px 40px, transparent 22px, var(--navy) 23px);
            background-size: 40px 40px;
            background-color: var(--navy-deep);
            opacity: 0.9;
        }
        .input-field {
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(22, 50, 79, 0.12);
        }
        .btn-primary:focus-visible, a:focus-visible, input:focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- Panel branding --}}
        <div class="relative hidden lg:flex flex-col justify-between p-12 kawung-pattern overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full border-2 flex items-center justify-center" style="border-color: var(--gold-light);">
                        <span class="font-display text-lg" style="color: var(--gold-light);">S7</span>
                    </div>
                    <span class="text-white/70 text-sm tracking-widest uppercase">SMK Negeri 7 Pekanbaru</span>
                </div>
            </div>

            <div class="relative z-10 max-w-md">
                <p class="font-display text-3xl leading-snug text-white">
                    Sistem Informasi<br>Persuratan Sekolah
                </p>
                <div class="mt-6 h-px w-16" style="background: var(--gold);"></div>
                <p class="mt-6 text-white/60 text-sm leading-relaxed">
                    Satu pintu untuk surat masuk, surat keluar, disposisi,
                    dan arsip — tata usaha, kurikulum, kesiswaan, dan sarana
                    prasarana dalam satu sistem.
                </p>
            </div>

            <p class="relative z-10 text-white/40 text-xs">
                &copy; {{ date('Y') }} SMK Negeri 7 Pekanbaru
            </p>
        </div>

        {{-- Panel form login --}}
        <div class="flex items-center justify-center p-8">
            <div class="w-full max-w-sm">

                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center" style="border-color: var(--gold);">
                        <span class="font-display text-base" style="color: var(--navy);">S7</span>
                    </div>
                    <span class="text-sm tracking-widest uppercase" style="color: var(--ink-muted);">SMKN 7 Pekanbaru</span>
                </div>

                <h1 class="font-display text-2xl" style="color: var(--navy);">Masuk ke sistem</h1>
                <p class="mt-1.5 text-sm" style="color: var(--ink-muted);">Gunakan username dan password akun kepegawaian Anda.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="input-field w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm bg-white"
                            placeholder="mis. admin.tu1"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium" style="color: var(--ink);">Password</label>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="input-field w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm bg-white"
                            placeholder="••••••••"
                        >
                    </div>

                    <label class="flex items-center gap-2 text-sm select-none" style="color: var(--ink-muted);">
                        <input type="checkbox" name="remember" class="rounded border-gray-300" style="accent-color: var(--navy);">
                        Ingat saya di perangkat ini
                    </label>

                    <button
                        type="submit"
                        class="btn-primary w-full rounded-lg py-2.5 text-sm font-medium text-white transition-opacity hover:opacity-90"
                        style="background: var(--navy);"
                    >
                        Masuk
                    </button>
                </form>

                <p class="mt-8 text-xs text-center" style="color: var(--ink-muted);">
                    Lupa password? Hubungi Admin TU untuk reset akun.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
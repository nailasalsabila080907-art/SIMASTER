<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Sistem Persuratan Sekolah</title>
    @vite('resources/css/app.css')
    <style>
        :root { --navy: #16324F; --gold: #C9972F; --paper: #F4F5F7; --ink: #23262B; --ink-muted: #767C86; }
        body { font-family: -apple-system, sans-serif; background: var(--paper); color: var(--ink); }
    </style>
</head>
<body class="min-h-screen">
    <nav class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-200">
        <span class="font-semibold" style="color: var(--navy);">Sistem Persuratan Sekolah</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">
                Keluar
            </button>
        </form>
    </nav>

    <main class="max-w-2xl mx-auto mt-16 px-6 text-center">
        <div class="w-14 h-14 rounded-full mx-auto flex items-center justify-center" style="background: var(--navy);">
            <span class="text-white text-xl">✓</span>
        </div>
        <h1 class="mt-6 text-2xl font-semibold" style="color: var(--navy);">Login berhasil</h1>
        <p class="mt-2" style="color: var(--ink-muted);">
            Selamat datang, {{ $user->pegawai->nama_lengkap ?? $user->username }}.
            Ini halaman dashboard sementara — fitur aslinya masih dalam pengerjaan.
        </p>

        <div class="mt-8 text-left bg-white rounded-xl border border-gray-200 p-6 text-sm">
            <p><span class="font-medium">Username:</span> {{ $user->username }}</p>
            <p class="mt-1"><span class="font-medium">Role:</span> {{ $user->role }}</p>
            <p class="mt-1"><span class="font-medium">Login terakhir:</span> {{ $user->last_login?->format('d M Y, H:i') ?? '—' }}</p>
        </div>
    </main>
</body>
</html>

@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Selamat datang kembali,</p>
        <h2 class="mb-1" style="font-size:1.5rem">{{ $user->pegawai->nama_lengkap ?? $user->username }}</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    @if($approvalSaya > 0)
        <a href="{{ route('approval.index') }}" class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
           style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
            <i class="bi bi-patch-exclamation"></i> {{ $approvalSaya }} approval menunggu
        </a>
    @endif
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    @php
        $statGradients = [
            'linear-gradient(135deg,#0F5C39,#178754)',
            'linear-gradient(135deg,#0EA5A4,#22C3A6)',
            'linear-gradient(135deg,#178754,#4FBE85)',
            'linear-gradient(135deg,#D98C00,#F0A202)',
            'linear-gradient(135deg,#3E4652,#5B5D6B)',
        ];
    @endphp
    @foreach($statistik as $i => $item)
        <div class="col-6 col-md-4 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white"
                              style="width:42px;height:42px;background:{{ $statGradients[$i % count($statGradients)] }};font-size:1.1rem">
                            {{ $item['icon'] }}
                        </span>
                        <span class="badge rounded-pill text-bg-light text-muted" style="font-size:.62rem">SIMASTER</span>
                    </div>
                    <p class="mb-0 fw-bold" style="font-size:1.65rem;color:var(--ink)">{{ $item['nilai'] }}</p>
                    <p class="text-muted mb-0" style="font-size:.78rem">{{ $item['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    {{-- Aktivitas surat chart --}}
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Aktivitas Surat</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Perbandingan surat masuk dan surat keluar 6 bulan terakhir</p>
                </div>
                <div class="d-flex gap-3" style="font-size:.75rem">
                    <span class="text-muted"><i class="bi bi-square-fill" style="color:#178754"></i> Surat Masuk</span>
                    <span class="text-muted"><i class="bi bi-square-fill" style="color:#8DD1AE"></i> Surat Keluar</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartAktivitasSurat" height="230"></canvas>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-start justify-content-between">
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Notifikasi Terbaru</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">{{ $notifikasiBelumDibaca }} belum dibaca</p>
                </div>
                <a href="{{ route('notifikasi.index') }}" class="text-primary" style="font-size:.78rem">Lihat semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifikasiTerbaru as $n)
                        <div class="list-group-item d-flex gap-3 py-3 px-3 border-0 border-bottom" style="min-width:0">
                            <a href="{{ route('notifikasi.tandai-dibaca',$n) }}"
                               onclick="event.preventDefault();document.getElementById('notif-{{ $n->id_notifikasi }}').submit();"
                               class="d-flex gap-3 text-decoration-none flex-grow-1" style="min-width:0">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                      style="width:34px;height:34px;background:{{ $n->sudah_dibaca ? '#F1F2F4' : '#FDF1E2' }};color:#F7A02A;font-size:.6rem">
                                    <i class="bi bi-dot fs-4"></i>
                                </span>
                                <span style="min-width:0;flex:1 1 auto">
                                    <span class="d-block fw-semibold text-truncate" style="font-size:.85rem;color:var(--ink)">{{ $n->judul }}</span>
                                    <span class="d-block text-muted notif-pesan" style="font-size:.76rem">{{ $n->pesan }}</span>
                                    @if(strlen($n->pesan) > 60)
                                        <a href="#" class="notif-toggle" style="font-size:.72rem;color:#178754"
                                           onclick="event.preventDefault();event.stopPropagation();
                                                    const p=this.previousElementSibling;
                                                    p.classList.toggle('notif-expanded');
                                                    this.textContent = p.classList.contains('notif-expanded') ? 'Sembunyikan' : 'Lihat selengkapnya';">
                                            Lihat selengkapnya
                                        </a>
                                    @endif
                                </span>
                            </a>
                        </div>
                        <form id="notif-{{ $n->id_notifikasi }}" method="POST" action="{{ route('notifikasi.tandai-dibaca',$n) }}" class="d-none">@csrf</form>
                    @empty
                        <div class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                            Tidak ada notifikasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Aktivitas terbaru --}}
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="mb-0" style="font-size:1.05rem">Aktivitas Terbaru</h3>
                <a href="{{ route('log-aktivitas.index') }}" class="text-primary" style="font-size:.78rem">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                            <th class="ps-3">Waktu</th>
                            <th>Aktivitas</th>
                            <th class="pe-3">Modul</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitasTerbaru as $log)
                            <tr>
                                <td class="ps-3 text-muted text-nowrap" style="font-size:.78rem">{{ $log->created_at->diffForHumans() }}</td>
                                <td style="font-size:.85rem">{{ $log->deskripsi ?? $log->aktivitas }}</td>
                                <td class="pe-3">
                                    <span class="badge rounded-pill text-bg-light text-muted" style="font-size:.72rem">{{ $log->modul ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5" style="font-size:.85rem">Belum ada aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Ringkasan kategori --}}
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="mb-1" style="font-size:1.05rem">Ringkasan Kategori</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Jumlah surat keluar berdasarkan kategori</p>
            </div>
            <div class="card-body">
                @php $maxKategori = max(1, $ringkasanKategori->max('jumlah') ?? 0); @endphp
                @forelse($ringkasanKategori as $r)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.82rem">
                            <span class="text-muted">{{ $r['nama'] }}</span>
                            <strong>{{ $r['jumlah'] }}</strong>
                        </div>
                        <div class="progress" style="height:7px;border-radius:99px;background:#EFEEF6">
                            <div class="progress-bar" role="progressbar"
                                 style="width:{{ ($r['jumlah'] / $maxKategori) * 100 }}%;background:#178754;border-radius:99px"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4 mb-0" style="font-size:.85rem">Belum ada data surat.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<style>
    .notif-pesan {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .notif-pesan.notif-expanded {
        -webkit-line-clamp: unset;
        overflow: visible;
    }
</style>
@endpush

@push('scripts')
<script>
    const ctx = document.getElementById('chartAktivitasSurat');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($grafik->pluck('label')),
            datasets: [
                {
                    label: 'Surat Masuk',
                    data: @json($grafik->pluck('masuk')),
                    backgroundColor: '#178754',
                    borderRadius: 6,
                    maxBarThickness: 22,
                },
                {
                    label: 'Surat Keluar',
                    data: @json($grafik->pluck('keluar')),
                    backgroundColor: '#8DD1AE',
                    borderRadius: 6,
                    maxBarThickness: 22,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#F0F0F5' }, ticks: { precision: 0 } }
            }
        }
    });
</script>
@endpush

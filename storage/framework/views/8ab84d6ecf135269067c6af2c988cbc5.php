<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">
            Selamat datang kembali,
        </p>

        <h2 class="mb-1" style="font-size:1.5rem">
            <?php echo e($user->pegawai->nama_lengkap ?? $user->username); ?>

        </h2>

        <p class="text-muted mb-0" style="font-size:.78rem">
            <?php echo e(now()->translatedFormat('l, d F Y')); ?>

        </p>
    </div>

    <div class="d-flex flex-wrap gap-2 justify-content-end">
    <?php $__currentLoopData = $aksiSuratMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($a['url']); ?>"
           class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
           style="
               background:linear-gradient(135deg,#DC2626,#EF4444);
               border:none;
               border-radius:10px;
               font-weight:600;
           ">
            <i class="bi bi-patch-exclamation"></i>
            <?php echo e($a['label']); ?>

        </a>        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($approvalSaya > 0): ?>
        <a href="<?php echo e(route('approval.index')); ?>"
           class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
           style="
               background:linear-gradient(135deg,#DC2626,#EF4444);
               border:none;
               border-radius:10px;
               font-weight:600;
           ">
            <i class="bi bi-patch-exclamation"></i>
            <?php echo e($approvalSaya); ?> approval menunggu
        </a>
    <?php endif; ?>
    </div>
</div>


<div class="row g-3 mb-4">

    <?php
        $statGradients = [
            'linear-gradient(135deg,#0F5C39,#178754)',
            'linear-gradient(135deg,#0EA5A4,#22C3A6)',
            'linear-gradient(135deg,#178754,#4FBE85)',
            'linear-gradient(135deg,#D98C00,#F0A202)',
            'linear-gradient(135deg,#3E4652,#5B5D6B)',
        ];
    ?>

    <?php $__currentLoopData = $statistik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="col-6 col-md-4 col-xl">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <span
                            class="d-inline-flex align-items-center justify-content-center rounded-3 text-white"
                            style="
                                width:42px;
                                height:42px;
                                background:<?php echo e($statGradients[$i % count($statGradients)]); ?>;
                                font-size:1.1rem;
                            ">
                            <?php echo e($item['icon']); ?>

                        </span>

                        <span
                            class="badge rounded-pill text-bg-light text-muted"
                            style="font-size:.62rem;">
                            SIMASTER
                        </span>

                    </div>

                    <p
                        class="mb-0 fw-bold"
                        style="font-size:1.65rem;color:var(--ink);">
                        <?php echo e($item['nilai']); ?>

                    </p>

                    <p
                        class="text-muted mb-0"
                        style="font-size:.78rem;">
                        <?php echo e($item['label']); ?>

                    </p>

                </div>

            </div>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">

            
            <div class="card-header">

                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">

                    <div>
                        <h3
                            class="mb-1"
                            style="font-size:1.05rem;">
                            Aktivitas Surat
                        </h3>

                        <p
                            class="text-muted mb-0"
                            style="font-size:.78rem;">
                            Jumlah surat masuk dan surat keluar
                        </p>
                    </div>
                        <form
                        method="GET"
                        action="<?php echo e(route('dashboard')); ?>"
                        class="d-flex flex-wrap align-items-center gap-2">

                        
                        <select
                            name="mode_grafik"
                            class="form-select form-select-sm"
                            style="
                                width:115px;
                                border-radius:9px;
                                font-size:.78rem;
                            ">

                            <option
                                value="bulan"
                                <?php echo e(($modeGrafik ?? 'bulan') === 'bulan' ? 'selected' : ''); ?>>
                                Per Bulan
                            </option>

                            <option
                                value="minggu"
                                <?php echo e(($modeGrafik ?? '') === 'minggu' ? 'selected' : ''); ?>>
                                Per Minggu
                            </option>

                        </select>


                        
                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="<?php echo e($tanggalMulai ?? now()->startOfYear()->format('Y-m-d')); ?>"
                            class="form-control form-control-sm"
                            style="
                                width:140px;
                                border-radius:9px;
                                font-size:.78rem;
                            ">


                        <span
                            class="text-muted"
                            style="font-size:.75rem;">
                            s/d
                        </span>


                        
                        <input
                            type="date"
                            name="tanggal_akhir"
                            value="<?php echo e($tanggalAkhir ?? now()->format('Y-m-d')); ?>"
                            class="form-control form-control-sm"
                            style="
                                width:140px;
                                border-radius:9px;
                                font-size:.78rem;
                            ">


                        
                        <button
                            type="submit"
                            class="btn btn-sm d-inline-flex align-items-center gap-1"
                            style="
                                background:var(--bs-primary);
                                color:#fff;
                                border-radius:9px;
                                font-size:.78rem;
                            ">

                            <i class="bi bi-filter"></i>

                            Tampilkan

                        </button>

                    </form>

                </div>


                
                <div
                    class="d-flex gap-3 mt-3"
                    style="font-size:.75rem;">

                    <span class="text-muted">
                        <i
                            class="bi bi-square-fill"
                            style="color:#178754;">
                        </i>
                        Surat Masuk
                    </span>

                    <span class="text-muted">
                        <i
                            class="bi bi-square-fill"
                            style="color:#8DD1AE;">
                        </i>
                        Surat Keluar
                    </span>

                </div>

            </div>


            
            <div class="card-body">

                
                <div
                    style="
                        position:relative;
                        height:220px;
                    ">

                    <canvas id="chartAktivitasSurat"></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-start justify-content-between">

                <div>
                    <h3
                        class="mb-1"
                        style="font-size:1.05rem;">
                        Notifikasi Terbaru
                    </h3>

                    <p
                        class="text-muted mb-0"
                        style="font-size:.78rem;">
                        <?php echo e($notifikasiBelumDibaca); ?> belum dibaca
                    </p>

                </div>

                <a
                    href="<?php echo e(route('notifikasi.index')); ?>"
                    class="text-primary"
                    style="font-size:.78rem;">
                    Lihat semua
                </a>

            </div>


            <div
                class="card-body p-0"
                style="
                    max-height:340px;
                    overflow-y:auto;
                ">

                <div class="list-group list-group-flush">

                    <?php $__empty_1 = true; $__currentLoopData = $notifikasiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div
                            class="list-group-item d-flex gap-3 py-3 px-3 border-0 border-bottom"
                            style="min-width:0;">

                            <a
                                href="<?php echo e(route('notifikasi.tandai-dibaca',$n)); ?>"
                                onclick="
                                    event.preventDefault();
                                    document.getElementById('notif-<?php echo e($n->id_notifikasi); ?>').submit();
                                "
                                class="d-flex gap-3 text-decoration-none flex-grow-1"
                                style="min-width:0;">

                                <span
                                    class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                    style="
                                        width:34px;
                                        height:34px;
                                        background:<?php echo e($n->sudah_dibaca ? '#F1F2F4' : '#FDF1E2'); ?>;
                                        color:#F7A02A;
                                        font-size:.6rem;
                                    ">

                                    <i class="bi bi-dot fs-4"></i>

                                </span>

                                <span
                                    style="
                                        min-width:0;
                                        flex:1 1 auto;
                                    ">

                                    <span
                                        class="d-block fw-semibold text-truncate"
                                        style="
                                            font-size:.85rem;
                                            color:var(--ink);
                                        ">

                                        <?php echo e($n->judul); ?>


                                    </span>


                                    <span
                                        class="d-block text-muted notif-pesan"
                                        style="font-size:.76rem;">

                                        <?php echo e($n->pesan); ?>


                                    </span>


                                    <?php if(strlen($n->pesan) > 60): ?>

                                        <a
                                            href="#"
                                            class="notif-toggle"
                                            style="
                                                font-size:.72rem;
                                                color:#178754;
                                            "
                                            onclick="
                                                event.preventDefault();
                                                event.stopPropagation();

                                                const p = this.previousElementSibling;

                                                p.classList.toggle('notif-expanded');

                                                this.textContent =
                                                    p.classList.contains('notif-expanded')
                                                    ? 'Sembunyikan'
                                                    : 'Lihat selengkapnya';
                                            ">

                                            Lihat selengkapnya

                                        </a>

                                    <?php endif; ?>

                                </span>

                            </a>

                        </div>


                        <form
                            id="notif-<?php echo e($n->id_notifikasi); ?>"
                            method="POST"
                            action="<?php echo e(route('notifikasi.tandai-dibaca',$n)); ?>"
                            class="d-none">

                            <?php echo csrf_field(); ?>

                        </form>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div
                            class="text-center text-muted py-5"
                            style="font-size:.85rem;">

                            <i
                                class="bi bi-bell-slash fs-3 d-block mb-2">
                            </i>

                            Tidak ada notifikasi.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row g-3">
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">

                <h3
                    class="mb-0"
                    style="font-size:1.05rem;">
                    Aktivitas Terbaru
                </h3>

                <a
                    href="<?php echo e(route('log-aktivitas.index')); ?>"
                    class="text-primary"
                    style="font-size:.78rem;">
                    Lihat semua
                </a>

            </div>


            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0">

                    <thead>

                        <tr
                            class="text-muted"
                            style="
                                font-size:.72rem;
                                text-transform:uppercase;
                                letter-spacing:.04em;
                            ">

                            <th class="ps-3">
                                Waktu
                            </th>

                            <th>
                                Aktivitas
                            </th>

                            <th class="pe-3">
                                Modul
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $aktivitasTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr>

                                <td
                                    class="ps-3 text-muted text-nowrap"
                                    style="font-size:.78rem;">

                                    <?php echo e($log->created_at->diffForHumans()); ?>


                                </td>


                                <td style="font-size:.85rem;">

                                    <?php echo e($log->deskripsi ?? $log->aktivitas); ?>


                                </td>


                                <td class="pe-3">

                                    <span
                                        class="badge rounded-pill text-bg-light text-muted"
                                        style="font-size:.72rem;">
                                        <?php echo e($log->modul ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>
                                <td
                                    colspan="3"
                                    class="text-center text-muted py-5"
                                    style="font-size:.85rem;">
                                    Belum ada aktivitas.
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header">

                <h3
                    class="mb-1"
                    style="font-size:1.05rem;">
                    Ringkasan Kategori
                </h3>

                <p
                    class="text-muted mb-0"
                    style="font-size:.78rem;">
                    Jumlah surat keluar berdasarkan kategori
                </p>

            </div>
            <div class="card-body">

                <?php
                    $maxKategori = max(
                        1,
                        $ringkasanKategori->max('jumlah') ?? 0
                    );
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $ringkasanKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="mb-3">
                        <div
                            class="d-flex justify-content-between mb-1"
                            style="font-size:.82rem;">

                            <span class="text-muted">
                                <?php echo e($r['nama']); ?>

                            </span>

                            <strong>
                                <?php echo e($r['jumlah']); ?>

                            </strong>

                        </div>

                        <div
                            class="progress"
                            style="
                                height:7px;
                                border-radius:99px;
                                background:#EFEEF6;
                            ">

                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="
                                    width:<?php echo e(($r['jumlah'] / $maxKategori) * 100); ?>%;
                                    background:#178754;
                                    border-radius:99px;
                                ">
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <p
                        class="text-muted text-center py-4 mb-0"
                        style="font-size:.85rem;">
                        Belum ada data surat.
                    </p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>

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

    .chart-filter-form {
        max-width: 100%;
    }

    @media (max-width: 1199.98px) {

        .chart-filter-form {
            width: 100%;
        }

    }

    @media (max-width: 575.98px) {

        .chart-filter-form select,
        .chart-filter-form input {
            width: 100% !important;
        }

        .chart-filter-form button {
            width: 100%;
            justify-content: center;
        }

    }

</style>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<script>

    const canvas = document.getElementById('chartAktivitasSurat');

    if (canvas) {

        new Chart(canvas, {

            type: 'bar',

            data: {

                labels: <?php echo json_encode($grafik->pluck('label'), 15, 512) ?>,

                datasets: [

                    {
                        label: 'Surat Masuk',

                        data: <?php echo json_encode($grafik->pluck('masuk'), 15, 512) ?>,

                        backgroundColor: '#178754',

                        borderRadius: 6,

                        maxBarThickness: 22,
                    },

                    {
                        label: 'Surat Keluar',

                        data: <?php echo json_encode($grafik->pluck('keluar'), 15, 512) ?>,

                        backgroundColor: '#8DD1AE',

                        borderRadius: 6,

                        maxBarThickness: 22,
                    }

                ]

            },
            options: {

                responsive: true,
                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return context.dataset.label
                                    + ': '
                                    + context.parsed.y
                                    + ' surat';
                            }
                        }
                    }
                },
                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {

                            maxRotation: 0,

                            autoSkip: true,

                            maxTicksLimit: 12

                        }

                    },


                    y: {

                        beginAtZero: true,

                        suggestedMax: 5,

                        grid: {
                            color: '#F0F0F5'
                        },

                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/dashboard.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Detail Surat Masuk'); ?>
<?php $__env->startSection('content'); ?>

<?php
    $badge = [
        'baru'                      => 'text-bg-warning',
        'menunggu_disposisi_kepsek' => 'text-bg-primary',
        'menunggu_disposisi_tu'     => 'text-bg-info',
        'didisposisi'               => 'text-bg-info',
        'diproses'                  => 'text-bg-primary',
        'selesai'                   => 'text-bg-success',
        'diarsipkan'                => 'text-bg-secondary',
    ][$suratMasuk->status] ?? 'text-bg-secondary';

    $labelStatus = [
        'baru'                      => 'Baru',
        'menunggu_disposisi_kepsek' => 'Menunggu Arahan Kepsek',
        'menunggu_disposisi_tu'     => 'Menunggu Disposisi TU',
        'didisposisi'               => 'Didisposisi',
        'diproses'                  => 'Diproses',
        'selesai'                   => 'Selesai',
        'diarsipkan'                => 'Diarsipkan',
    ][$suratMasuk->status] ?? ucfirst($suratMasuk->status);
?>

<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-start gap-3">
        <a href="<?php echo e(route('surat-masuk.index')); ?>" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Persuratan / Surat Masuk</p>
            <h2 class="mb-1" style="font-size:1.4rem"><?php echo e($suratMasuk->perihal); ?></h2>
            <p class="text-muted mb-0" style="font-size:.78rem">Dari <?php echo e($suratMasuk->asal_instansi); ?> &middot; Agenda <?php echo e($suratMasuk->nomor_surat_masuk); ?></p>
        </div>
    </div>
    <span class="badge rounded-pill <?php echo e($badge); ?>" style="font-size:.75rem;padding:.5rem .9rem"><?php echo e($labelStatus); ?></span>
</div>

<?php if(session('sukses')): ?>
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div><?php echo e(session('sukses')); ?></div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-3">
    <?php
        $ringkasan = [
            ['icon' => 'bi-file-earmark-text', 'label' => 'Nomor Asal', 'nilai' => $suratMasuk->nomor_surat_asal ?? '-', 'grad' => 'linear-gradient(135deg,#0F5C39,#178754)'],
            ['icon' => 'bi-calendar3', 'label' => 'Tanggal Surat', 'nilai' => $suratMasuk->tanggal_surat?->format('d/m/Y') ?? '-', 'grad' => 'linear-gradient(135deg,#0EA5A4,#22C3A6)'],
            ['icon' => 'bi-calendar-check', 'label' => 'Diterima', 'nilai' => $suratMasuk->tanggal_diterima?->format('d/m/Y'), 'grad' => 'linear-gradient(135deg,#178754,#4FBE85)'],
            ['icon' => 'bi-flag', 'label' => 'Sifat', 'nilai' => ucfirst($suratMasuk->sifat_surat), 'grad' => 'linear-gradient(135deg,#D98C00,#F0A202)'],
        ];
    ?>
    <?php $__currentLoopData = $ringkasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                          style="width:38px;height:38px;background:<?php echo e($r['grad']); ?>;font-size:1rem">
                        <i class="bi <?php echo e($r['icon']); ?>"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-muted mb-0 text-truncate" style="font-size:.72rem"><?php echo e($r['label']); ?></p>
                        <p class="fw-semibold mb-0 text-truncate" style="font-size:.9rem"><?php echo e($r['nilai']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if($suratMasuk->file_scan_path): ?>
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:44px;height:44px;background:#E6F5EC;color:#178754">
                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                </div>

                <div>
                    <h6 class="mb-1 fw-semibold" style="font-size:.9rem">
                        Lampiran Surat Masuk
                    </h6>

                    <p class="text-muted mb-1" style="font-size:.78rem">
                        Dokumen hasil scan surat asli
                    </p>

                    <span class="text-muted" style="font-size:.75rem">
                        <?php echo e(basename($suratMasuk->file_scan_path)); ?>

                    </span>
                </div>
            </div>

            <a href="<?php echo e(asset('storage/' . $suratMasuk->file_scan_path)); ?>"
               target="_blank"
               class="btn btn-sm d-inline-flex align-items-center gap-2 text-white"
               style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                <i class="bi bi-eye"></i>
                Lihat Scan Surat
            </a>

        </div>
    </div>
<?php endif; ?>




<?php if($suratMasuk->status === 'baru' && in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true)): ?>
    <div class="card mb-3 border-warning">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1" style="font-size:1rem">Ajukan ke Kepala Sekolah</h5>
                <p class="text-muted mb-0" style="font-size:.8rem">Surat baru masuk. Ajukan ke Kepala Sekolah untuk mendapatkan catatan atau arahan.</p>
            </div>
            <form method="POST" action="<?php echo e(route('surat-masuk.ajukan-kepsek', $suratMasuk)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-warning btn-sm text-dark fw-semibold">
                    <i class="bi bi-arrow-up-right-circle"></i> Ajukan ke Kepala Sekolah
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>




<?php if($suratMasuk->status === 'menunggu_disposisi_kepsek' && auth()->user()->role === 'kepala_sekolah'): ?>
    <div class="card mb-3 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0" style="font-size:1rem"><i class="bi bi-journal-text"></i> Lembar Disposisi / Arahan Kepala Sekolah</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('surat-masuk.beri-arahan', $suratMasuk)); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Catatan / Arahan Pimpinan</label>
                    <textarea name="catatan_kepsek" rows="4" class="form-control" required placeholder="Tulis catatan atau arahan di sini (contoh: 'Dilanjutkan ke bidang terkait' atau 'Arsipkan saja')..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Pilih Aksi Lanjutan</label>
                    <select name="aksi_kepsek" class="form-select" required>
                        <option value="lanjut_tu">Teruskan ke Admin TU untuk Disposisi</option>
                        <option value="arsip">Langsung Arsipkan Surat Ini</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send"></i> Kirim Arahan
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


<?php if($suratMasuk->catatan_kepsek): ?>
    <div class="card mb-3 bg-light border-success">
        <div class="card-body">
            <h6 class="text-success mb-1" style="font-size:.8rem"><i class="bi bi-check-circle"></i> Catatan / Arahan Kepala Sekolah:</h6>
            <p class="mb-0 fw-semibold" style="font-size:.9rem; color: #333;"><?php echo e($suratMasuk->catatan_kepsek); ?></p>
        </div>
    </div>
<?php endif; ?>




<?php if($suratMasuk->status === 'menunggu_disposisi_tu' && in_array(auth()->user()->role, ['admin_tu', 'super_admin'])): ?>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                  style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                <i class="bi bi-signpost-split"></i>
            </span>
            <div>
                <h3 class="mb-1" style="font-size:1.05rem">Buat Disposisi</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Kepala Sekolah sudah memberi arahan. Pilih satu atau lebih pegawai/unit tujuan.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('surat-masuk.disposisikan', $suratMasuk)); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tujuan Pegawai (boleh pilih lebih dari satu)</label>
                        <select name="tujuan_pegawai[]" class="form-select" multiple size="6">
                            <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id_pegawai); ?>"><?php echo e($p->nama_lengkap); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tujuan Unit Kerja (boleh pilih lebih dari satu)</label>
                        <select name="tujuan_unit[]" class="form-select" multiple size="6">
                            <?php $__currentLoopData = $unitList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id_unit); ?>"><?php echo e($u->nama_unit); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Instruksi</label>
                        <input type="text" name="instruksi" class="form-control" placeholder="Mohon ditindaklanjuti">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Catatan Tambahan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                        <i class="bi bi-send"></i> Kirim Disposisi
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


<?php if($suratMasuk->disposisi->isNotEmpty()): ?>
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="mb-1" style="font-size:1.2rem">Riwayat Disposisi</h3>
            <p class="text-muted mb-0" style="font-size:.95rem"><?php echo e($suratMasuk->disposisi->count()); ?> disposisi tercatat</p>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php $__currentLoopData = $suratMasuk->disposisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $warnaTitik = match($d->status) {
                            'selesai' => ['bg' => '#E6F5EC', 'fg' => '#178754'],
                            'ditolak' => ['bg' => '#FCEBEA', 'fg' => '#C4463F'],
                            default   => ['bg' => '#FDF1E2', 'fg' => '#F7A02A'],
                        };
                        $badgeStatus = match($d->status) {
                            'selesai' => 'text-bg-success',
                            'ditolak' => 'text-bg-danger',
                            default   => 'text-bg-light text-muted',
                        };
                        $bolehAdmin = in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true);
                        $bolehAksi = $bolehAdmin
                            || $d->ke_pegawai === auth()->user()->pegawai?->id_pegawai
                            || ($d->ke_unit && auth()->user()->adalahAdminUnit($d->ke_unit));
                        
                        $modeUnitSederhana = $d->ke_unit && ! $bolehAdmin;
                        $statusFinal = in_array($d->status, ['selesai', 'ditolak'], true);
                    ?>
                    <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-3 border-0 border-bottom">
                        <div class="d-flex gap-3 min-w-0">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                  style="width:34px;height:34px;background:<?php echo e($warnaTitik['bg']); ?>;color:<?php echo e($warnaTitik['fg']); ?>;font-size:.6rem">
                                <i class="bi bi-dot fs-4"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="d-block fw-semibold text-truncate" style="font-size:1rem;color:var(--ink)">Ke: <?php echo e($d->tujuan_label); ?></span>
                                <span class="d-block text-muted" style="font-size:.9rem">Dari <?php echo e($d->pemberiDisposisi->nama_lengkap ?? '-'); ?> &middot; <?php echo e($d->tanggal_disposisi?->diffForHumans()); ?></span>
                                <?php if($d->instruksi): ?>
                                    <span class="d-block mt-1" style="font-size:.95rem"><?php echo e($d->instruksi); ?></span>
                                <?php endif; ?>
                                <?php if($d->status === 'ditolak' && $d->catatan): ?>
                                    <span class="d-block mt-1 text-danger" style="font-size:.95rem"><i class="bi bi-info-circle"></i> <?php echo e($d->catatan); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="badge rounded-pill <?php echo e($badgeStatus); ?>" style="font-size:.85rem;padding:.45rem .7rem">
                                <?php echo e(ucfirst($d->status)); ?>

                            </span>

                            <a href="<?php echo e(route('disposisi.show', $d)); ?>" class="btn btn-sm btn-light" style="font-size:.9rem">
                                Lihat Detail
                            </a>

                            <?php if(! $statusFinal && $bolehAksi): ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size:.9rem">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if($modeUnitSederhana): ?>
                                            <li>
                                                <form method="POST" action="<?php echo e(route('disposisi.terima', $d)); ?>"
                                                      onsubmit="return confirm('Terima disposisi surat ini? Setelah diterima, statusnya langsung dianggap selesai.')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item" style="font-size:.9rem">
                                                        <i class="bi bi-check2 me-1"></i> Terima
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" style="font-size:.9rem"
                                                        data-bs-toggle="modal" data-bs-target="#tolakModal<?php echo e($d->id_disposisi); ?>">
                                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                                </button>
                                            </li>
                                        <?php else: ?>
                                            <?php if($d->status !== 'ditindaklanjuti'): ?>
                                                <li>
                                                    <form method="POST" action="<?php echo e(route('disposisi.tindaklanjuti', $d)); ?>"
                                                          onsubmit="return confirm('Tandai disposisi ini mulai ditindaklanjuti?')">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item" style="font-size:.9rem">
                                                            <i class="bi bi-play-circle me-1"></i> Tindak Lanjuti
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <form method="POST" action="<?php echo e(route('disposisi.selesaikan', $d)); ?>"
                                                      onsubmit="return confirm('Tandai disposisi ini selesai?')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item" style="font-size:.9rem">
                                                        <i class="bi bi-check2 me-1"></i> Tandai Selesai
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" style="font-size:.9rem"
                                                        data-bs-toggle="modal" data-bs-target="#tolakModal<?php echo e($d->id_disposisi); ?>">
                                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                                </button>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <div class="modal fade" id="tolakModal<?php echo e($d->id_disposisi); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="<?php echo e(route('disposisi.tolak', $d)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title" style="font-size:1rem">Tolak Disposisi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label" style="font-size:.9rem">Alasan penolakan</label>
                                                    <textarea name="catatan_penolakan" rows="3" class="form-control" required placeholder="Jelaskan kenapa disposisi ini ditolak"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size:.9rem">Batal</button>
                                                    <button type="submit" class="btn btn-danger" style="font-size:.9rem">Tolak Disposisi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($suratMasuk->status === 'selesai' && in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true)): ?>
    <div class="d-flex justify-content-end mt-3 pt-3 border-top">
        <form method="POST"
              action="<?php echo e(route('arsip.surat-masuk', $suratMasuk)); ?>"
              onsubmit="return confirm('Yakin ingin mengarsipkan surat ini?')">
            <?php echo csrf_field(); ?>

            <button type="submit"
                    class="btn d-inline-flex align-items-center gap-2 text-white"
                    style="background:linear-gradient(135deg,#178754,#0F5C39);border:none">
                <i class="bi bi-archive"></i>
                Arsipkan Surat
            </button>
        </form>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/surat-masuk/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Surat Masuk'); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Daftar Surat Masuk</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Pencatatan surat, disposisi, tindak lanjut, dan arsip.</p>
    </div>
    <?php if(Auth::user()->role !== 'kepala_sekolah'): ?>
        <a href="<?php echo e(route('surat-masuk.create')); ?>" class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
            <i class="bi bi-plus-lg"></i> Catat Surat Masuk
        </a>
    <?php endif; ?>
</div>


<?php if(session('sukses')): ?>
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div><?php echo e(session('sukses')); ?></div>
    </div>
<?php endif; ?>

<?php if(session('gagal')): ?>
    <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2" style="font-size:.85rem" role="alert">
        <i class="bi bi-x-circle"></i>
        <div><?php echo e(session('gagal')); ?></div>
    </div>
<?php endif; ?>


<ul class="nav mb-4" style="border-bottom:1px solid var(--border);">
    <li class="nav-item">
        <a class="nav-link active px-3 py-2" href="<?php echo e(route('surat-masuk.index')); ?>"
           style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
            Data Aktif
        </a>
    </li>
    <?php if(in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true)): ?>
        <li class="nav-item">
            <a class="nav-link px-3 py-2" href="<?php echo e(route('surat-masuk.trashed')); ?>"
               style="color:var(--ink-muted);font-weight:600;">
                Sampah
            </a>
        </li>
    <?php endif; ?>
</ul>
    <?php if($filterDisposisi): ?>
    <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between gap-2 py-2" style="font-size:.85rem">
        <span>
            <i class="bi bi-funnel me-1"></i>
            <?php if($filterDisposisi === 'ditolak'): ?>
                Menampilkan surat yang punya <strong>disposisi ditolak</strong>. Buka suratnya, lalu klik <strong>Ubah Disposisi</strong>.
            <?php else: ?>
                Menampilkan surat yang punya <strong>disposisi menunggu respons Anda</strong>. Buka suratnya, lalu terima atau tolak.
            <?php endif; ?>
        </span>
        <a href="<?php echo e(route('surat-masuk.index')); ?>" class="btn btn-sm btn-light">Tampilkan semua</a>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">        
           <?php $__currentLoopData = ['' => 'Semua', 'baru' => 'Baru', 'menunggu_disposisi_kepsek' => 'Menunggu Arahan Kepsek', 'menunggu_disposisi_tu' => 'Menunggu Disposisi TU', 'didisposisi' => 'Didisposisi', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'diarsipkan' => 'Diarsipkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isActive = ($filterSuratMasuk === $val) || (!$filterSuratMasuk && !$val); ?>
                <a href="<?php echo e(route('surat-masuk.index', $val ? ['status' => $val] : [])); ?>"
                   class="badge rounded-pill text-decoration-none <?php echo e($isActive ? 'text-white' : 'text-bg-light text-muted'); ?>"
                   style="font-size:.78rem;padding:.5rem .9rem;<?php echo e($isActive ? 'background:linear-gradient(135deg,#178754,#0EA5A4)' : ''); ?>">
                    <?php echo e($label); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <span class="text-muted" style="font-size:.78rem"><?php echo e($suratMasuk->total()); ?> surat</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Agenda</th>
                    <th>Asal Instansi</th>
                    <th>Perihal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $suratMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $badge = [
                            'baru'        => 'text-bg-warning',
                            'menunggu_disposisi_kepsek' => 'text-bg-primary',
                            'menunggu_disposisi_tu' => 'text-bg-info',
                            'didisposisi' => 'text-bg-info',
                            'diproses'    => 'text-bg-primary',
                            'selesai'     => 'text-bg-success',
                            'diarsipkan'  => 'text-bg-secondary',
                        ][$s->status] ?? 'text-bg-secondary';

                        $avatarGradients = [
                            'linear-gradient(135deg,#0F5C39,#178754)',
                            'linear-gradient(135deg,#0EA5A4,#22C3A6)',
                            'linear-gradient(135deg,#178754,#4FBE85)',
                            'linear-gradient(135deg,#D98C00,#F0A202)',
                            'linear-gradient(135deg,#3E4652,#5B5D6B)',
                        ];
                    ?>
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem"><?php echo e($s->nomor_surat_masuk); ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                                      style="width:32px;height:32px;font-size:.72rem;background:<?php echo e($avatarGradients[$i % count($avatarGradients)]); ?>">
                                    <?php echo e(strtoupper(substr($s->asal_instansi, 0, 1))); ?>

                                </span>
                                <span style="font-size:.85rem"><?php echo e($s->asal_instansi); ?></span>
                            </div>
                        </td>
                        <td>
                            <a href="<?php echo e(route('surat-masuk.show', $s)); ?>" class="fw-semibold text-decoration-none" style="font-size:.85rem;color:var(--ink)">
                                <?php echo e($s->perihal); ?>

                            </a>
                            <?php if($s->disposisi_ditolak_count > 0): ?>
                                <span class="badge text-bg-danger ms-1" style="font-size:.68rem">
                                    <?php echo e($s->disposisi_ditolak_count); ?> disposisi ditolak
                                </span>
                            <?php endif; ?>
                            <?php if(($filterDisposisi ?? null) === 'menunggu' && $s->disposisi_menunggu_count > 0): ?>
                                <span class="badge text-bg-warning ms-1" style="font-size:.68rem">menunggu respons</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem"><?php echo e($s->tanggal_diterima?->format('d M Y')); ?></td>
                        <td>
                            <?php
                                $labelStatus = [
                                    'baru'        => 'Baru',
                                    'menunggu_disposisi_kepsek' => 'Menunggu Arahan Kepsek',
                                    'menunggu_disposisi_tu' => 'Menunggu Disposisi TU',
                                    'didisposisi' => 'Didisposisi',
                                    'diproses'    => 'Diproses',
                                    'selesai'     => 'Selesai',
                                    'diarsipkan'  => 'Diarsipkan',
                                ][$s->status] ?? ucfirst($s->status);
                            ?>
                            <span class="badge rounded-pill <?php echo e($badge); ?>" style="font-size:.72rem"><?php echo e($labelStatus); ?></span>
                        </td>
                        <td class="pe-3 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="<?php echo e(route('surat-masuk.show', $s)); ?>" class="btn btn-sm btn-light rounded-circle" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if($s->diterima_oleh === Auth::id() || in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true)): ?>
                                    <form action="<?php echo e(route('surat-masuk.destroy', $s)); ?>" method="POST"
                                          onsubmit="return confirm('Hapus surat masuk ini? Surat akan dipindahkan ke sampah.')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-light rounded-circle text-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada surat masuk.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($suratMasuk->hasPages()): ?>
        <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="text-muted" style="font-size:.78rem">
                Menampilkan <?php echo e($suratMasuk->firstItem()); ?>–<?php echo e($suratMasuk->lastItem()); ?> dari <?php echo e($suratMasuk->total()); ?> data
            </span>
            <?php echo e($suratMasuk->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/surat-masuk/index.blade.php ENDPATH**/ ?>
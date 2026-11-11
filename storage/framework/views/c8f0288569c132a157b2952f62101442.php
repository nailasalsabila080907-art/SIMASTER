<?php $__env->startSection('title', 'Surat Keluar'); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Daftar Surat Keluar</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Kelola draft, pengajuan, approval, dan surat yang sudah terbit.</p>
    </div>
   <?php if(Auth::user()->role !== 'kepala_sekolah'): ?>
        <a href="<?php echo e(route('surat-keluar.create')); ?>" class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
           style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
            <i class="bi bi-plus-lg"></i> Buat Surat
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
        <a class="nav-link active px-3 py-2" href="<?php echo e(route('surat-keluar.index')); ?>"
           style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
            Data Aktif
        </a>
    </li>
    <?php if(in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true)): ?>
        <li class="nav-item">
            <a class="nav-link px-3 py-2" href="<?php echo e(route('surat-keluar.trashed')); ?>"
               style="color:var(--ink-muted);font-weight:600;">
                Nonaktif
            </a>
        </li>
    <?php endif; ?>
</ul>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php $__currentLoopData = ['' => 'Semua', 'draft' => 'Draft', 'ditolak' => 'Ditolak', 'diajukan' => 'Diajukan', 'terkirim' => 'Terbit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isActive = ($filterStatus === $val) || (!$filterStatus && !$val); ?>
                <a href="<?php echo e(route('surat-keluar.index', $val ? ['status' => $val] : [])); ?>"
                   class="badge rounded-pill text-decoration-none <?php echo e($isActive ? 'text-white' : 'text-bg-light text-muted'); ?>"
                   style="font-size:.78rem;padding:.5rem .9rem;<?php echo e($isActive ? 'background:linear-gradient(135deg,#178754,#0EA5A4)' : ''); ?>">
                    <?php echo e($label); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <span class="text-muted" style="font-size:.78rem"><?php echo e($suratKeluar->total()); ?> surat</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Nomor</th>
                    <th>Perihal</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Pembuat</th>
                    <th>Status</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $suratKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $badge = [
                            'draft'      => 'text-bg-secondary',
                            'diajukan'   => 'text-bg-warning',
                            'disetujui'  => 'text-bg-success',
                            'ditolak'    => 'text-bg-danger',
                            'terkirim'   => 'text-bg-primary',
                            'diarsipkan' => 'text-bg-secondary',
                        ][$s->status] ?? 'text-bg-secondary';
                    ?>
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem"><?php echo e($s->nomor_surat ?? '—'); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('surat-keluar.show', $s)); ?>" class="fw-semibold text-decoration-none" style="font-size:.85rem;color:var(--ink)">
                                <?php echo e($s->perihal); ?>

                            </a>
                        </td>
                        <td class="text-muted" style="font-size:.82rem"><?php echo e($s->kategori->nama_kategori ?? '-'); ?></td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem"><?php echo e($s->tanggal_surat?->format('d M Y') ?? '-'); ?></td>
                        <td class="text-muted" style="font-size:.82rem"><?php echo e($s->pembuat->pegawai->nama_lengkap ?? $s->pembuat->username ?? '-'); ?></td>
                        <td>
                            <span class="badge rounded-pill <?php echo e($badge); ?>" style="font-size:.72rem"><?php echo e(ucfirst($s->status)); ?></span>
                        </td>
                        <td class="pe-3 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="<?php echo e(route('surat-keluar.show', $s)); ?>" class="btn btn-sm btn-light rounded-circle" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                               <?php if((in_array($s->status, ['draft', 'ditolak'], true) && ($s->dibuat_oleh === Auth::id() || in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true) )
                                )|| ( $s->status === 'diarsipkan' && in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true))): ?>
                                    <form action="<?php echo e(route('surat-keluar.destroy', $s)); ?>" method="POST"
                                          onsubmit="return confirm('Hapus surat ini? Surat akan dipindahkan ke nonaktif.')">
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
                        <td colspan="7" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-send fs-3 d-block mb-2"></i>
                            Belum ada surat keluar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

        <?php if($suratKeluar->hasPages()): ?>
         <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
         <span class="text-muted" style="font-size:.78rem">
             Menampilkan <?php echo e($suratKeluar->firstItem()); ?>–<?php echo e($suratKeluar->lastItem()); ?> dari <?php echo e($suratKeluar->total()); ?> data
         </span>
        <?php echo e($suratKeluar->links('pagination::bootstrap-5')); ?>

    </div>
<?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/surat-keluar/index.blade.php ENDPATH**/ ?>
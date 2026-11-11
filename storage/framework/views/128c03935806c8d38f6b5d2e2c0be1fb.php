<?php $__env->startSection('title', 'Sampah Surat Masuk'); ?>
<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Sampah Surat Masuk</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Surat yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.</p>
    </div>
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
        <a class="nav-link px-3 py-2" href="<?php echo e(route('surat-masuk.index')); ?>"
           style="color:var(--ink-muted);font-weight:600;">
            Data Aktif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active px-3 py-2" href="<?php echo e(route('surat-masuk.trashed')); ?>"
           style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
            Sampah
        </a>
    </li>
</ul>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Agenda</th>
                    <th>Asal Instansi</th>
                    <th>Perihal</th>
                    <th>Dihapus pada</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $suratMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem"><?php echo e($s->nomor_surat_masuk); ?></span>
                        </td>
                        <td style="font-size:.85rem"><?php echo e($s->asal_instansi); ?></td>
                        <td class="fw-semibold" style="font-size:.85rem;color:var(--ink)"><?php echo e($s->perihal); ?></td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem"><?php echo e($s->deleted_at?->translatedFormat('d M Y, H:i')); ?></td>
                        <td class="pe-3 text-end">
                            <div class="d-inline-flex gap-2 justify-content-end">
                                <form action="<?php echo e(route('surat-masuk.restore', $s->uuid)); ?>" method="POST"
                                      onsubmit="return confirm('Pulihkan surat masuk ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                    <button type="submit" class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:#1B8A5A;font-weight:600;background:#fff;font-size:.78rem;">
                                        <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                    </button>
                                </form>
                                <?php if(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true)): ?>
                                    <form action="<?php echo e(route('surat-masuk.forceDelete', $s->uuid)); ?>" method="POST"
                                          onsubmit="return confirm('Hapus PERMANEN surat masuk ini? Data tidak bisa dikembalikan lagi.')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;font-size:.78rem;">
                                            <i class="bi bi-trash3-fill"></i> Hapus Permanen
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-trash3 fs-3 d-block mb-2"></i>
                            Sampah kosong.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($suratMasuk->hasPages()): ?>
        <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <?php echo e($suratMasuk->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/surat-masuk/trashed.blade.php ENDPATH**/ ?>
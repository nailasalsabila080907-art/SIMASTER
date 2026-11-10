<?php $__env->startSection('title', 'Approval Surat'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Persetujuan Surat</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Surat yang menunggu persetujuan Anda.
            </p>
        </div>
        <span class="badge rounded-pill" style="background:var(--primary-light);color:var(--bs-primary);font-weight:700;font-size:.78rem;padding:.5rem .9rem;">
            <?php echo e($approvalSaya->total()); ?> menunggu
        </span>
    </div>

    
    
    

    
    <?php $__empty_1 = true; $__currentLoopData = $approvalSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;border-radius:12px;background:var(--primary-light);color:var(--bs-primary);">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-semibold" style="color:var(--ink)">
                                <?php echo e($a->suratKeluar->perihal); ?>

                            </p>
                            <div class="d-flex flex-wrap align-items-center gap-2" style="font-size:.78rem;color:var(--ink-muted);">
                                <span class="badge" style="background:var(--surface);color:#5B5D6B;font-weight:600;border:1px solid var(--border);">
                                    <?php echo e($a->suratKeluar->kategori->nama_kategori); ?>

                                </span>
                                <span>&middot;</span>
                                <span>Diajukan oleh <?php echo e($a->suratKeluar->pembuat->pegawai->nama_lengkap ?? $a->suratKeluar->pembuat->username); ?></span>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo e(route('surat-keluar.show', $a->suratKeluar)); ?>"
                       class="btn btn-sm flex-shrink-0"
                       style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                        Lihat detail <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                    <i class="bi bi-inbox fs-4"></i>
                </div>
                <p class="mb-0" style="color:var(--ink-muted);">
                    Tidak ada surat yang menunggu persetujuan Anda saat ini.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if($approvalSaya->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($approvalSaya->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/approval/index.blade.php ENDPATH**/ ?>
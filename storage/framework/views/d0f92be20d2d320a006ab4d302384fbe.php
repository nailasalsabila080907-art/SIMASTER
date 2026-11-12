<?php $__env->startSection('title', 'Notifikasi'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Notifikasi</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Semua notifikasi untuk Anda.
            </p>
        </div>
        <form method="POST" action="<?php echo e(route('notifikasi.tandai-semua')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm d-inline-flex align-items-center gap-1"
                    style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                <i class="bi bi-check2-all"></i> Tandai Semua Sudah Dibaca
            </button>
        </form>
    </div>

    
    <?php if(session('sukses')): ?>
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div><?php echo e(session('sukses')); ?></div>
        </div>
    <?php endif; ?>

    
    <div class="d-flex flex-column gap-2">
        <?php $__empty_1 = true; $__currentLoopData = $notifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <form method="POST" action="<?php echo e(route('notifikasi.tandai-dibaca', $n)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="card w-100 text-start border-0"
                        style="border-radius:12px;background:<?php echo e($n->sudah_dibaca ? '#fff' : 'var(--primary-light)'); ?>;">
                    <div class="card-body d-flex align-items-start gap-3 py-3 px-4">
                        <?php if (! ($n->sudah_dibaca)): ?>
                            <span class="rounded-circle flex-shrink-0 mt-1" style="width:8px;height:8px;background:var(--bs-primary);"></span>
                        <?php else: ?>
                            <span class="flex-shrink-0" style="width:8px;height:8px;"></span>
                        <?php endif; ?>
                        <div class="flex-fill">
                            <p class="mb-0 fw-semibold" style="font-size:.87rem;color:var(--ink);"><?php echo e($n->judul); ?></p>
                            <p class="mb-0 mt-1" style="font-size:.83rem;color:var(--ink-muted);"><?php echo e($n->pesan); ?></p>
                            <p class="mb-0 mt-2" style="font-size:.72rem;color:var(--ink-muted);"><?php echo e($n->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                </button>
            </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card">
                <div class="card-body text-center py-5" style="color:var(--ink-muted);">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                        <i class="bi bi-bell fs-4"></i>
                    </div>
                    <p class="mb-0">Belum ada notifikasi.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($notifikasi->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($notifikasi->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/notifikasi/index.blade.php ENDPATH**/ ?>
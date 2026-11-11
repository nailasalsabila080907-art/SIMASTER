<?php $__env->startSection('title', $jurusan->exists ? 'Ubah Jurusan' : 'Tambah Jurusan'); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="<?php echo e(route('jurusan.index')); ?>" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                <?php echo e($jurusan->exists ? 'Ubah Jurusan' : 'Tambah Jurusan'); ?>

            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Lengkapi data jurusan di bawah ini.
            </p>
        </div>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-body p-4">

            <?php if($errors->any()): ?>
                <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                    <i class="bi bi-exclamation-circle-fill mt-1"></i>
                    <div>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="mb-0"><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e($jurusan->exists ? route('jurusan.update', $jurusan) : route('jurusan.store')); ?>">
                <?php echo csrf_field(); ?>
                <?php if($jurusan->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-diagram-3"></i> Data Jurusan
                </p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kode</label>
                        <input type="text" name="kode_jurusan" value="<?php echo e(old('kode_jurusan', $jurusan->kode_jurusan)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" value="<?php echo e(old('nama_jurusan', $jurusan->nama_jurusan)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="aktif" <?php echo e(old('status', $jurusan->status ?: 'aktif') === 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="nonaktif" <?php echo e(old('status', $jurusan->status) === 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="<?php echo e(route('jurusan.index')); ?>" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/master/jurusan/form.blade.php ENDPATH**/ ?>
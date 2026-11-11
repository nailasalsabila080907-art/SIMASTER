<?php $__env->startSection('title', 'Pengguna Sistem'); ?>

<?php $__env->startSection('content'); ?>

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Pengguna Sistem</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
            Kelola username, role, status, dan password akun pegawai.
        </p>
        <ul class="nav mb-4" style="border-bottom:1px solid var(--border);">
    <li class="nav-item">
        <a class="nav-link active px-3 py-2" href="<?php echo e(route('pengguna.index')); ?>"
           style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
            Data Aktif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link px-3 py-2" href="<?php echo e(route('pengguna.trashed')); ?>"
           style="color:var(--ink-muted);font-weight:600;">
            Data Terhapus
        </a>
    </li>
</ul>
    </div>

    <?php if(session('sukses')): ?>
        <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#EAF7EE;color:#2E7D4F;border-radius:12px;font-size:.85rem;">
            <i class="bi bi-check-circle-fill mt-1"></i>
            <div><?php echo e(session('sukses')); ?></div>
        </div>
    <?php endif; ?>

    <?php if(session('gagal')): ?>
        <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
            <i class="bi bi-exclamation-circle-fill mt-1"></i>
            <div><?php echo e(session('gagal')); ?></div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size:.87rem;">
                    <thead style="background:var(--bs-light,#f8f9fa);">
                        <tr>
                            <th class="ps-4 py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Pengguna</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Jabatan</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Role</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Status</th>
                            <th class="pe-4 py-3 text-end" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pengguna; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-top:1px solid var(--border);">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                             style="width:34px;height:34px;background:#EEF1FF;color:var(--bs-primary);font-weight:700;font-size:.8rem;">
                                            <?php echo e(strtoupper(substr($u->username, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color:var(--ink)"><?php echo e($u->username); ?></div>
                                            <div style="color:var(--ink-muted);font-size:.78rem"><?php echo e($u->pegawai->nama_lengkap ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3"><?php echo e($u->pegawai->jabatan->nama_jabatan ?? '-'); ?></td>
                                <td class="py-3">
                                    <span class="badge" style="background:#EEF1FF;color:var(--bs-primary);font-weight:600;">
                                        <?php echo e(ucwords(str_replace('_', ' ', $u->role))); ?>

                                    </span>
                                </td>
                                <td class="py-3">
                                    <?php if($u->status === 'aktif'): ?>
                                        <span class="badge" style="background:#EAF7EE;color:#2E7D4F;font-weight:600;">Aktif</span>
                                    <?php else: ?>
                                       setlass="badge" style="background:#F1F1F3;color:#6B7280;font-weight:600;">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="<?php echo e(route('pengguna.edit', $u)); ?>" class="btn-icon-ghost me-2" title="Ubah">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <?php if($u->id_user !== auth()->id()): ?>
                                        <form class="d-inline" method="POST" action="<?php echo e(route('pengguna.destroy', $u)); ?>" onsubmit="return confirm('Hapus akun ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-icon-ghost text-danger" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color:var(--ink-muted)">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada akun.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <?php echo e($pengguna->links()); ?>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/master/pengguna/index.blade.php ENDPATH**/ ?>
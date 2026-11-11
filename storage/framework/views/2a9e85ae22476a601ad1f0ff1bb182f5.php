<?php $__env->startSection('title', 'Riwayat Surat'); ?>

<?php $__env->startSection('content'); ?>

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Riwayat Surat</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
            <?php if($bolehLihatSemua): ?>
                Riwayat aktivitas seluruh surat masuk & surat keluar.
            <?php else: ?>
                Riwayat aktivitas surat yang Anda kelola.
            <?php endif; ?>
        </p>
    </div>

    
    <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <?php if($bolehLihatSemua): ?>
            <select name="user_id" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
                <option value="">Semua pengguna</option>
                <?php $__currentLoopData = $daftarUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id_user); ?>" <?php echo e((string) $filterUserId === (string) $u->id_user ? 'selected' : ''); ?>>
                        <?php echo e($u->pegawai->nama_lengkap ?? $u->username); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php endif; ?>

        <select name="tipe_surat" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua jenis surat</option>
            <option value="masuk" <?php echo e($filterTipeSurat === 'masuk' ? 'selected' : ''); ?>>Surat Masuk</option>
            <option value="keluar" <?php echo e($filterTipeSurat === 'keluar' ? 'selected' : ''); ?>>Surat Keluar</option>
        </select>

        <select name="aktivitas" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua aktivitas</option>
            <?php $__currentLoopData = ['dibuat','diedit','diajukan','disposisi','tindak_lanjut','disposisi_selesai','selesai','approve','tolak','terbit','arsip','dihapus','cetak_pdf']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($opsi); ?>" <?php echo e($filterAktivitas === $opsi ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $opsi))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php if($filterUserId || $filterTipeSurat || $filterAktivitas): ?>
            <a href="<?php echo e(route('log-surat.index')); ?>" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
                <i class="bi bi-x-circle"></i> Reset filter
            </a>
        <?php endif; ?>
    </form>

    
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <?php if($bolehLihatSemua): ?>
                            <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengguna</th>
                        <?php endif; ?>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Surat</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aktivitas</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Keterangan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-top:1px solid var(--border);">
                            <?php if($bolehLihatSemua): ?>
                                <td class="px-4 py-3 fw-semibold" style="color:var(--ink);"><?php echo e($log->user->pegawai->nama_lengkap ?? $log->user->username ?? '-'); ?></td>
                            <?php endif; ?>
                            <td class="px-4 py-3" style="color:var(--ink);">
                                <span class="badge" style="background:<?php echo e($log->tipe_surat === 'masuk' ? '#E6F0FA' : '#FDF3E3'); ?>;color:<?php echo e($log->tipe_surat === 'masuk' ? '#1B5FA8' : '#B8760A'); ?>;font-weight:600;font-size:.72rem;border-radius:20px;">
                                    <?php echo e($log->tipe_surat === 'masuk' ? 'Surat Masuk' : 'Surat Keluar'); ?>

                                </span>
                                <div style="font-size:.78rem;color:var(--ink-muted);margin-top:.15rem;">
                                    <?php echo e($log->surat?->perihal ?? '-'); ?>

                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge" style="background:var(--primary-light);color:var(--primary-dark);font-weight:600;font-size:.72rem;border-radius:20px;">
                                    <?php echo e(ucwords(str_replace('_', ' ', $log->aktivitas))); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink);"><?php echo e($log->keterangan ?? '-'); ?></td>
                            <td class="px-4 py-3 text-nowrap" style="color:var(--ink-muted);font-size:.8rem;"><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e($bolehLihatSemua ? 5 : 4); ?>" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada riwayat surat tercatat.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($logs->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($logs->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/log-surat/index.blade.php ENDPATH**/ ?>
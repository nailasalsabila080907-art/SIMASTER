<?php $__env->startSection('title', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Log Aktivitas</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
            <?php if($bolehLihatSemua): ?>
                Riwayat aktivitas seluruh pengguna sistem.
            <?php else: ?>
                Riwayat aktivitas Anda di sistem.
            <?php endif; ?>
        </p>
    </div>

    
    <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <div class="position-relative" style="width:260px;">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--ink-muted);font-size:.85rem;"></i>
            <input
                type="text"
                name="cari"
                value="<?php echo e($filterCari); ?>"
                placeholder="Cari pengguna, modul, deskripsi..."
                class="form-control form-control-sm"
                style="border-radius:10px;border-color:var(--border);font-size:.85rem;padding-left:32px;"
            >
        </div>

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

        <select name="aktivitas" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua aktivitas</option>
            <?php $__currentLoopData = $daftarAktivitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($opsi); ?>" <?php echo e($filterAktivitas === $opsi ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $opsi))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php if($filterUserId || $filterAktivitas || $filterCari): ?>
            <a href="<?php echo e(route('log-aktivitas.index')); ?>" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
                <i class="bi bi-x-circle"></i> Reset filter
            </a>
        <?php endif; ?>

        <button type="submit" class="btn btn-sm" style="border-radius:10px;background:var(--bs-primary);color:#fff;font-size:.85rem;">
            <i class="bi bi-search me-1"></i> Cari
        </button>
    </form>

    
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <th class="px-4 py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Waktu</th>
                        <?php if($bolehLihatSemua): ?>
                            <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengguna</th>
                            <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Role</th>
                        <?php endif; ?>
                        <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                        <th class="py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Modul / Deskripsi</th>
                        <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Alamat IP</th>
                        <th class="px-4 py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Perangkat / Browser</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-top:1px solid var(--border);">
                            
                            <td class="px-4 py-3 text-nowrap" style="color:var(--ink-muted);font-size:.8rem;">
                                <?php echo e($log->created_at->format('d/m/Y H:i:s')); ?>

                            </td>

                            <?php if($bolehLihatSemua): ?>
                                
                                <td class="py-3 fw-semibold text-nowrap" style="color:var(--ink);">
                                    <?php echo e($log->user->pegawai->nama_lengkap ?? $log->user->username ?? 'User Terhapus'); ?>

                                </td>

                                
                                <td class="py-3 text-nowrap">
                                    <?php
                                        $roleColors = [
                                            'super_admin'     => 'background:#fde2e2;color:#9c1c1c;',
                                            'admin_tu'        => 'background:#dbeafe;color:#1d4ed8;',
                                            'kepala_sekolah'  => 'background:#ede9fe;color:#5b21b6;',
                                        ];
                                        $roleStyle = $roleColors[$log->role] ?? 'background:#f1f5f9;color:#475569;';
                                    ?>
                                    <span class="badge" style="<?php echo e($roleStyle); ?>font-weight:600;font-size:.7rem;border-radius:20px;padding:.35em .7em;">
                                        <?php echo e($log->role ? ucwords(str_replace('_', ' ', $log->role)) : '-'); ?>

                                    </span>
                                </td>
                            <?php endif; ?>

                            
                            <td class="py-3 text-nowrap">
                                <?php
                                    $aksiLabel = match($log->aktivitas) {
                                        'login' => 'Login',
                                        'logout' => 'Logout',
                                        'lihat_halaman' => 'Detail',
                                        'index' => 'Index',
                                        'show' => 'Show',
                                        'create' => 'Create',
                                        'edit' => 'Edit',
                                        'trash' => 'Sampah',
                                        'tambah_data' => 'Create',
                                        'ubah_data' => 'Update',
                                        'hapus_data' => 'Delete',
                                        'forceDelete' => 'Delete Permanen',
                                        default => ucwords(str_replace('_', ' ', $log->aktivitas)),
                                    };
                                    $aksiColors = [
                                        'Login' => 'background:#dcfce7;color:#166534;',
                                        'Logout' => 'background:#f1f5f9;color:#475569;',
                                        'Detail' => 'background:#e2d1f9;color:#5a3791;',
                                        'Index' => 'background:#e2e8f0;color:#334155;',
                                        'Show' => 'background:#e2d1f9;color:#5a3791;',
                                        'Create' => 'background:#dbeafe;color:#1d4ed8;',
                                        'Edit' => 'background:#fef3c7;color:#92400e;',
                                        'Sampah' => 'background:#fde2e2;color:#9c1c1c;',
                                        'Create' => 'background:#dbeafe;color:#1d4ed8;',
                                        'Update' => 'background:#fef3c7;color:#92400e;',
                                        'Delete' => 'background:#fde2e2;color:#9c1c1c;',
                                        'Restore' => 'background:#dcfce7;color:#166534;',
                                        'Hapus Permanen' => 'background:#fde2e2;color:#9c1c1c;',
                                    ];
                                    $aksiStyle = $aksiColors[$aksiLabel] ?? 'background:var(--primary-light);color:var(--primary-dark);';
                                ?>
                                <span class="badge text-uppercase" style="<?php echo e($aksiStyle); ?>font-weight:700;font-size:.68rem;letter-spacing:.03em;border-radius:6px;padding:.35em .7em;">
                                    <?php echo e($aksiLabel); ?>

                                </span>
                            </td>

                            
                            <td class="py-3" style="color:var(--ink);">
                                <?php if($log->modul): ?>
                                    <strong class="d-block small text-muted mb-1">[<?php echo e($log->modul); ?>]</strong>
                                <?php endif; ?>
                                <span style="white-space:normal;word-break:break-word;">
                                    <?php echo e($log->deskripsi ?? '-'); ?>

                                </span>
                            </td>

                            
                            <td class="py-3 text-nowrap">
                                <code style="font-size:.78rem;font-weight:600;color:var(--bs-primary);background:var(--surface);padding:.2em .5em;border-radius:6px;">
                                    <?php echo e($log->ip_address ?? '-'); ?>

                                </code>
                            </td>

                            
                            <td class="px-4 py-3" style="color:var(--ink);font-size:.8rem;white-space:normal;word-break:break-word;min-width:240px;">
                                <div class="fw-semibold"><?php echo e($log->perangkat); ?></div>
                                <div class="text-muted" style="font-size:.7rem;word-break:break-all;"><?php echo e($log->user_agent ?? '-'); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e($bolehLihatSemua ? 7 : 5); ?>" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada aktivitas tercatat.</p>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/log-aktivitas/index.blade.php ENDPATH**/ ?>
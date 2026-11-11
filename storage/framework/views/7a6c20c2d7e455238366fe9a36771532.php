<?php $__env->startSection('title', 'Arsip Surat'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
    <div>
        <h5 class="mb-1" style="color:var(--ink)">Arsip Surat</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
            Daftar surat yang telah selesai diproses dan diarsipkan.
        </p>
    </div>

    <?php if(Auth::user()->role === 'kepala_sekolah'): ?>
    <a href="<?php echo e(route('approval.index')); ?>"
       class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
       style="background:linear-gradient(135deg,#178754,#0EA5A4);
              border:none;
              border-radius:10px;
              font-size:.85rem;
              font-weight:600;">
        <i class="bi bi-patch-check"></i>
        Approval
    </a>
<?php endif; ?>
</div>

    
    <form method="GET" action="<?php echo e(route('arsip.index')); ?>" class="d-flex flex-wrap align-items-center gap-2 mb-3">

    
    <div class="position-relative" style="width:260px;">
        <i
            class="bi bi-search position-absolute"
            style="left:12px;top:50%;transform:translateY(-50%);color:var(--ink-muted);font-size:.85rem;"
        ></i>

        <input
            type="text"
            name="cari"
            value="<?php echo e($filterCari); ?>"
            placeholder="Cari nomor atau perihal surat..."
            class="form-control form-control-sm"
            style="border-radius:10px;border-color:var(--border);font-size:.85rem;padding-left:32px;"
        >
    </div>

    
    <?php if($bolehLihatSemua): ?>
        <select name="user_id" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;"
        >
            <option value="">Semua pengguna</option>

            <?php $__currentLoopData = $daftarUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    value="<?php echo e($u->id_user); ?>"
                    <?php echo e((string) $filterUserId === (string) $u->id_user ? 'selected' : ''); ?>

                >
                    <?php echo e($u->pegawai->nama_lengkap ?? $u->username); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    <?php endif; ?>

    
    <select name="tipe_surat" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;"
    >
        <option value="">Semua jenis surat</option>

        <option
            value="masuk"
            <?php echo e($filterTipeSurat === 'masuk' ? 'selected' : ''); ?>

        >
            Surat Masuk
        </option>

        <option value="keluar" <?php echo e($filterTipeSurat === 'keluar' ? 'selected' : ''); ?>>Surat Keluar</option> </select>

    
    <button type="submit" class="btn btn-sm" style="border-radius:10px;background:var(--bs-primary);color:#fff;font-size:.85rem;" >
        <i class="bi bi-search me-1"></i>
        Cari
    </button>

    
    <?php if($filterCari || $filterUserId || $filterTipeSurat): ?>
        <a
            href="<?php echo e(route('arsip.index')); ?>" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
            <i class="bi bi-x-circle"></i>
            Reset filter
        </a>
    <?php endif; ?>

</form>

    
    <?php if(session('sukses')): ?>
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div><?php echo e(session('sukses')); ?></div>
        </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Jenis</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nomor / ID</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Diarsipkan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengarsip</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $arsip; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $surat = $a->suratTerkait(); ?>
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3">
                                <?php if($a->tipe_surat === 'keluar'): ?>
                                    <span class="badge" style="background:var(--primary-light);color:var(--bs-primary);font-weight:600;">
                                        <i class="bi bi-send"></i> Keluar
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background:#EAF4FB;color:var(--info);font-weight:600;">
                                        <i class="bi bi-envelope-arrow-down"></i> Masuk
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-monospace" style="font-size:.8rem;color:var(--ink);">
                                <?php echo e($surat->nomor_surat ?? $surat->nomor_surat_masuk ?? ('#'.$a->id_surat)); ?>

                            </td>
                            <td class="px-4 py-3" style="color:var(--ink-muted);">
                                <?php echo e($a->tanggal_diarsipkan?->format('d/m/Y H:i')); ?>

                            </td>
                            <td class="px-4 py-3">
                                <?php echo e($a->pengarsip->pegawai->nama_lengkap ?? $a->pengarsip->username ?? '-'); ?>

                            </td>
                            <td class="px-4 py-3 text-end">
                                <?php if($surat): ?>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo e($a->tipe_surat === 'keluar' ? route('surat-keluar.show', $surat) : route('surat-masuk.show', $surat)); ?>"
                                           class="btn btn-sm"
                                           style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <?php if($a->tipe_surat === 'keluar' && in_array($surat->status, ['terkirim', 'diarsipkan'], true)): ?>
                                            <a href="<?php echo e(route('surat-keluar.cetak-pdf', $surat)); ?>"
                                               class="btn btn-sm"
                                               target="_blank"
                                               style="border:1px solid #2F6B4F;border-radius:8px;color:#2F6B4F;font-weight:600;background:#fff;">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        <?php endif; ?>
                                        <?php if(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true)): ?>
                                            <form action="<?php echo e($a->tipe_surat === 'keluar' ? route('surat-keluar.destroy', $surat) : route('surat-masuk.destroy', $surat)); ?>"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus surat ini dari arsip? Surat akan dipindahkan ke sampah dan bisa dipulihkan nanti.')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm"
                                                        style="border:1px solid var(--border);border-radius:8px;color:#C4463F;font-weight:600;background:#fff;">
                                                    <i class="bi bi-trash3"></i> Hapus
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-archive fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada arsip.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($arsip->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($arsip->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/arsip/index.blade.php ENDPATH**/ ?>
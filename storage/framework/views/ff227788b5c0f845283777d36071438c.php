<?php $__env->startSection('title', 'Detail Surat'); ?>
<?php $__env->startSection('content'); ?>

<?php
    $badge = [
        'draft'      => 'text-bg-secondary',
        'diajukan'   => 'text-bg-warning',
        'disetujui'  => 'text-bg-success',
        'ditolak'    => 'text-bg-danger',
        'terkirim'   => 'text-bg-primary',
        'diarsipkan' => 'text-bg-secondary',
    ][$suratKeluar->status] ?? 'text-bg-secondary';
?>

<div style="max-width:760px">

    <div class="d-flex align-items-start gap-3 mb-4">
        <?php if (isset($component)) { $__componentOriginal7df061e68ce253759b3c26462a603095 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7df061e68ce253759b3c26462a603095 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tombol-kembali','data' => ['href' => route('surat-keluar.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tombol-kembali'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('surat-keluar.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7df061e68ce253759b3c26462a603095)): ?>
<?php $attributes = $__attributesOriginal7df061e68ce253759b3c26462a603095; ?>
<?php unset($__attributesOriginal7df061e68ce253759b3c26462a603095); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7df061e68ce253759b3c26462a603095)): ?>
<?php $component = $__componentOriginal7df061e68ce253759b3c26462a603095; ?>
<?php unset($__componentOriginal7df061e68ce253759b3c26462a603095); ?>
<?php endif; ?>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Persuratan / Surat Keluar</p>
            <h2 class="mb-0" style="font-size:1.4rem">Detail Surat</h2>
        </div>
    </div>

    

    <div class="card mb-3">
        <div class="card-body">

            <?php if(! $suratKeluar->isDraft()): ?>
                
                <?php
                    $logoKiri = $sekolah?->logo_path
                        ? asset('storage/' . $sekolah->logo_path)
                        : null;
                    $logoKanan = asset('images/logo-tut-wuri.png.jpg');
                ?>
                <div class="d-flex align-items-center gap-3 pb-3 mb-4" style="border-bottom:3px double #000;">
                    <div class="flex-shrink-0" style="width:68px;text-align:center;">
                        <?php if($logoKiri): ?>
                            <img src="<?php echo e($logoKiri); ?>" alt="Logo Sekolah" style="width:72px;height:100px;object-fit:contain;transform:translateX(+20px);">
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1 text-center" style="font-family:'Times New Roman', Times, serif; color:#000;">
                        <p class="mb-0 fw-bold" style="font-size:1.05rem;">PEMERINTAH PROVINSI RIAU</p>
                        <p class="mb-0 fw-bold" style="font-size:1rem;">DINAS PENDIDIKAN</p>
                        <p class="mb-1 fw-bold" style="font-size:.88rem;">SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 7 PEKANBARU</p>
                        <p class="mb-1" style="font-size:.68rem;">
                            <?php echo e($sekolah->alamat ?? '-'); ?> <?php echo e($sekolah->kota ?? 'Pekanbaru'); ?> <?php echo e($sekolah->provinsi ?? 'Riau'); ?> <?php echo e($sekolah->kode_pos ?? ''); ?>

                        </p>
                        <p class="mb-1" style="font-size:.68rem;">
                            E-mail: <?php echo e($sekolah->email ?? '-'); ?> &nbsp;&nbsp; Website: <?php echo e($sekolah->website ?? '-'); ?> &nbsp;&nbsp; Telp: <?php echo e($sekolah->telepon ?? '-'); ?>

                        </p>
                        <p class="mb-0" style="font-size:.68rem;">
                            NPSN: <?php echo e($sekolah->npsn ?? '-'); ?> &nbsp;&nbsp; NSS: <?php echo e($sekolah->nss ?? '16120632160'); ?>

                        </p>
                    </div>
                    <div class="flex-shrink-0" style="width:75px;text-align:center;">
                        <img src="<?php echo e($logoKanan); ?>" alt="Tut Wuri Handayani" style="width:100px;height:100px;object-fit:contain;transform:translateX(-27px);">
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h3 class="mb-1" style="font-size:1.15rem"><?php echo e($suratKeluar->perihal); ?></h3>
                    <p class="text-muted mb-0" style="font-size:.82rem">
                        <?php echo e($suratKeluar->kategori->nama_kategori); ?> &middot; <?php echo e($suratKeluar->tanggal_surat?->format('d M Y')); ?>

                    </p>
                </div>
                <span class="badge rounded-pill <?php echo e($badge); ?>" style="font-size:.75rem;padding:.5rem .9rem"><?php echo e(ucfirst($suratKeluar->status)); ?></span>
            </div>

            <?php if($suratKeluar->nomor_surat): ?>
                <span class="d-inline-block font-monospace text-muted mt-3" style="font-size:.82rem;background:var(--surface);border-radius:8px;padding:.5rem .85rem">
                    <?php echo e($suratKeluar->nomor_surat); ?>

                </span>
            <?php endif; ?>

            <?php if($suratKeluar->isi_surat): ?>
                <div class="mt-4 pt-4 border-top" style="font-size:.88rem">
                    <?php echo $suratKeluar->isi_surat; ?>

                </div>
            <?php else: ?>
                <div class="mt-4 pt-4 border-top">
                    <div class="row g-3">
                        <?php $__currentLoopData = $suratKeluar->data_variabel ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $varDef = $suratKeluar->template->variabel->firstWhere('nama_variabel', $key); ?>
                            <div class="col-md-6">
                                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em"><?php echo e($varDef?->label ?? $key); ?></p>
                                <?php if($varDef?->tipe_input === 'richtext'): ?>
                                    <div class="fw-semibold mb-0" style="font-size:.88rem"><?php echo $value; ?></div>
                                <?php else: ?>
                                    <p class="fw-semibold mb-0" style="font-size:.88rem"><?php echo e($value); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(in_array($suratKeluar->status, ['draft', 'ditolak']) && ($suratKeluar->dibuat_oleh === auth()->id() || in_array(auth()->user()->role, ['admin_tu', 'super_admin']))): ?>
                <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                    <a href="<?php echo e(route('surat-keluar.edit', $suratKeluar)); ?>" class="btn btn-light">Ubah Draft</a>
                    <form method="POST" action="<?php echo e(route('surat-keluar.ajukan', $suratKeluar)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                                style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                            <i class="bi bi-send"></i> Ajukan untuk Persetujuan
                        </button>
                    </form>
                </div>
            <?php endif; ?>

                    <?php if(in_array($suratKeluar->status, ['terkirim', 'diarsipkan'], true)): ?>
                        <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                            <?php if(
            $suratKeluar->status === 'terkirim' &&
            in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true)
        ): ?>
            <form method="POST"
                  action="<?php echo e(route('arsip.surat-keluar', $suratKeluar)); ?>"
                  onsubmit="return confirm('Yakin ingin mengarsipkan surat ini?')">
                <?php echo csrf_field(); ?>
                        
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-archive"></i> Arsipkan Surat
                </button>
            </form>
        <?php endif; ?>
                    <a href="<?php echo e(route('surat-keluar.cetak-pdf', $suratKeluar)); ?>" target="_blank"
                       class="btn d-inline-flex align-items-center gap-2 text-white"
                       style="background:linear-gradient(135deg,#D98C00,#F0A202);border:none">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if($approvalSaya): ?>
        <div class="card mb-3" style="border:1px solid var(--bs-primary)">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-patch-check" style="color:var(--bs-primary);font-size:1.1rem"></i>
                    <h3 class="mb-0" style="font-size:1rem">Surat ini menunggu persetujuan Anda</h3>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm text-white"
                        style="background:var(--bs-primary);border-radius:8px;font-weight:600;"
                        data-bs-toggle="modal" data-bs-target="#modalKonfirmasiSetujuiDetail">
                        <i class="bi bi-check2"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        style="border-radius:8px;font-weight:600;"
                        data-bs-toggle="collapse" data-bs-target="#tolakDariDetail">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
                <div class="collapse mt-3" id="tolakDariDetail">
                    <form method="POST" action="<?php echo e(route('approval.tolak', ['approval' => $approvalSaya->id_approval])); ?>">
                        <?php echo csrf_field(); ?>
                        <textarea name="catatan" rows="3" required
                                    class="form-control mb-2"
                                    style="border-radius:10px;border-color:var(--border);font-size:.85rem;"
                                    placeholder="Masukkan alasan penolakan..."></textarea>
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;font-weight:600;">
                            Konfirmasi Tolak
                        </button>
                    </form> 
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="modalKonfirmasiSetujuiDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px;border:none;">
                    <div class="modal-body text-center p-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width:56px;height:56px;border-radius:50%;background:var(--primary-light);">
                            <i class="bi bi-patch-check" style="font-size:1.5rem;color:var(--bs-primary);"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Setujui surat ini?</h5>
                        <p class="text-muted mb-4" style="font-size:.85rem;">
                            Surat "<?php echo e($suratKeluar->perihal); ?>" akan disetujui dan diteruskan ke tahap berikutnya.
                        </p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal" style="border-radius:10px;">
                                Batal
                            </button>
                            <button type="submit" form="formSetujuiDetail" class="btn w-50"
                                    style="border-radius:10px;background:var(--bs-primary);color:#fff;">
                                Ya, Setujui
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="formSetujuiDetail" method="POST" action="<?php echo e(route('approval.setujui', ['approval' => $approvalSaya->id_approval])); ?>">
            <?php echo csrf_field(); ?>
        </form>
    <?php endif; ?>

    
    <?php if($suratKeluar->approval->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="mb-1" style="font-size:1.05rem">Riwayat Persetujuan</h3>
                <p class="text-muted mb-0" style="font-size:.78rem"><?php echo e($suratKeluar->approval->count()); ?> tahap persetujuan</p>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $suratKeluar->approval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $wStatus = [
                                'menunggu'  => ['bg' => '#FDF1E2', 'ink' => '#F7A02A', 'badge' => 'text-bg-light text-muted'],
                                'disetujui' => ['bg' => '#E6F5EC', 'ink' => '#178754', 'badge' => 'text-bg-success'],
                                'ditolak'   => ['bg' => '#FBEAE7', 'ink' => '#E5484D', 'badge' => 'text-bg-danger'],
                            ][$a->status] ?? ['bg' => '#F1F2F4', 'ink' => '#8B8D97', 'badge' => 'text-bg-light text-muted'];
                        ?>
                        <div class="list-group-item d-flex align-items-center justify-content-between gap-3 py-3 px-3 border-0 border-bottom">
                            <div class="d-flex gap-3 min-w-0">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                      style="width:34px;height:34px;background:<?php echo e($wStatus['bg']); ?>;color:<?php echo e($wStatus['ink']); ?>;font-size:.6rem">
                                    <i class="bi bi-dot fs-4"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="d-block fw-semibold text-truncate" style="font-size:.85rem;color:var(--ink)">
                                        <?php echo e($a->pegawaiPemberiApproval->nama_lengkap ?? '-'); ?>

                                    </span>
                                    <?php if($a->catatan): ?>
                                        <span class="d-block text-muted" style="font-size:.76rem"><?php echo e($a->catatan); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="badge rounded-pill <?php echo e($wStatus['badge']); ?> flex-shrink-0" style="font-size:.72rem"><?php echo e(ucfirst($a->status)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/surat-keluar/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Catat Surat Masuk'); ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:960px">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?php echo e(route('surat-masuk.index')); ?>" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Persuratan / Surat Masuk</p>
            <h2 class="mb-0" style="font-size:1.5rem">Catat Surat Masuk</h2>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-3 d-flex gap-2" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <p class="fw-semibold mb-1">Periksa kembali isian berikut:</p>
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('surat-masuk.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#0F5C39,#178754);font-size:1rem">
                    <i class="bi bi-envelope-paper"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Informasi Surat</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Asal, kategori, dan perihal surat yang diterima</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Nomor Surat Asal</label>
                        <input type="text" name="nomor_surat_asal" value="<?php echo e(old('nomor_surat_asal')); ?>" class="form-control" placeholder="Contoh: 421/123/DIS-PEND/2026">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Asal Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="asal_instansi" value="<?php echo e(old('asal_instansi')); ?>" class="form-control <?php $__errorArgs = ['asal_instansi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: Dinas Pendidikan Provinsi" required>
                        <?php $__errorArgs = ['asal_instansi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-size:.85rem">Perihal <span class="text-danger">*</span></label>
                        <input type="text" name="perihal" value="<?php echo e(old('perihal')); ?>" class="form-control <?php $__errorArgs = ['perihal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ringkasan isi surat" required>
                        <?php $__errorArgs = ['perihal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select <?php $__errorArgs = ['id_kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id_kategori); ?>" <?php echo e(old('id_kategori') == $k->id_kategori ? 'selected' : ''); ?>>
                                    <?php echo e($k->nama_kategori); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['id_kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Klasifikasi Arsip <span class="text-danger">*</span></label>
                        <select name="id_klasifikasi" class="form-select <?php $__errorArgs = ['id_klasifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Klasifikasi --</option>
                            <?php $__currentLoopData = $klasifikasiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id_klasifikasi); ?>" <?php echo e(old('id_klasifikasi') == $k->id_klasifikasi ? 'selected' : ''); ?>>
                                    <?php echo e($k->kode_klasifikasi); ?> - <?php echo e($k->nama_klasifikasi); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['id_klasifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#D98C00,#F0A202);font-size:1rem">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Tanggal &amp; Sifat</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Kapan surat dibuat, diterima, dan tingkat kepentingannya</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="<?php echo e(old('tanggal_surat')); ?>" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Tanggal Diterima <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_diterima" value="<?php echo e(old('tanggal_diterima', now()->format('Y-m-d'))); ?>" class="form-control <?php $__errorArgs = ['tanggal_diterima'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__errorArgs = ['tanggal_diterima'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Sifat Surat</label>
                        <select name="sifat_surat" class="form-select">
                            <?php $__currentLoopData = ['biasa' => 'Biasa', 'penting' => 'Penting', 'segera' => 'Segera', 'rahasia' => 'Rahasia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>" <?php echo e(old('sifat_surat') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#3E4652,#5B5D6B);font-size:1rem">
                    <i class="bi bi-paperclip"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Lampiran</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Unggah hasil pindai (opsional)</p>
                </div>
            </div>
            <div class="card-body">
                <label class="form-label" style="font-size:.85rem">Scan Surat</label>
                <input type="file" name="file_scan" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                <div class="form-text" style="font-size:.78rem">Format PDF, JPG, atau PNG.</div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?php echo e(route('surat-masuk.index')); ?>" class="btn btn-light">Batal</a>
            <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                    style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                <i class="bi bi-save"></i> Simpan Surat Masuk
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/surat-masuk/create.blade.php ENDPATH**/ ?>
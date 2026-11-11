<?php $__env->startSection('title', $pegawai->exists ? 'Ubah Pegawai' : 'Tambah Pegawai'); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="<?php echo e(route('pegawai.index')); ?>" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                <?php echo e($pegawai->exists ? 'Ubah Pegawai' : 'Tambah Pegawai'); ?>

            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Lengkapi data pegawai di bawah ini.
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

            <form method="POST" action="<?php echo e($pegawai->exists ? route('pegawai.update', $pegawai) : route('pegawai.store')); ?>">
                <?php echo csrf_field(); ?>
                <?php if($pegawai->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-person-vcard"></i> Data Pegawai
                </p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap', $pegawai->nama_lengkap)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="<?php echo e(old('gelar_depan', $pegawai->gelar_depan)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="<?php echo e(old('gelar_belakang', $pegawai->gelar_belakang)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">NIP</label>
                        <input type="text" name="nip" value="<?php echo e(old('nip', $pegawai->nip)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                            <option value="L" <?php echo e(old('jenis_kelamin', $pegawai->jenis_kelamin) === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('jenis_kelamin', $pegawai->jenis_kelamin) === 'P' ? 'selected' : ''); ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jabatan</label>
                        <select name="id_jabatan" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="">-- Pilih jabatan --</option>
                            <?php $__currentLoopData = $jabatanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j->id_jabatan); ?>" <?php echo e((string) old('id_jabatan', $pegawai->id_jabatan) === (string) $j->id_jabatan ? 'selected' : ''); ?>>
                                    <?php echo e($j->nama_jabatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Unit Kerja</label>
                        <select name="id_unit" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="">-- Pilih unit --</option>
                            <?php $__currentLoopData = $unitList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id_unit); ?>" <?php echo e((string) old('id_unit', $pegawai->id_unit) === (string) $u->id_unit ? 'selected' : ''); ?>>
                                    <?php echo e($u->nama_unit); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Pangkat/Golongan</label>
                        <input type="text" name="pangkat_golongan" value="<?php echo e(old('pangkat_golongan', $pegawai->pangkat_golongan)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" value="<?php echo e(old('no_hp', $pegawai->no_hp)); ?>"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>
                </div>

                <?php if (! ($pegawai->exists)): ?>
                    <hr class="my-4" style="border-color:var(--border);">

                    <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                        <i class="bi bi-shield-lock"></i> Akun Login
                        <span class="text-normal fw-normal text-lowercase" style="color:var(--ink-muted);letter-spacing:normal;">(opsional, boleh dikosongkan)</span>
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="username" value="<?php echo e(old('username')); ?>"
                                   class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password"
                                   class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Role</label>
                            <select name="role" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                                <?php $__currentLoopData = ['admin_tu' => 'Admin TU', 'kepala_sekolah' => 'Kepala Sekolah', 'staff' => 'Staff', 'guru' => 'Guru', 'super_admin' => 'Super Admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="<?php echo e(route('pegawai.index')); ?>" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/master/pegawai/form.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', $suratKeluar?->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">
                Persuratan / Surat Keluar
            </p>

            <h2 class="mb-1" style="font-size:1.5rem">
                <?php echo e($suratKeluar?->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar'); ?>

            </h2>

            <p class="text-muted mb-0" style="font-size:.78rem">
                Pilih kategori surat, lalu lengkapi datanya.
            </p>
        </div>
    </div>


    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-3 d-flex gap-2 mb-4" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle mt-1"></i>

            <div>
                <p class="fw-semibold mb-1">
                    Periksa kembali isian berikut:
                </p>

                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>


    
    <div class="row g-3">
        <div class="col-xl-4 col-xxl-3">

            <div class="d-flex flex-column gap-3">
                <div class="card">

                    <div class="card-header d-flex align-items-center gap-3">

                        <span
                            class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                background:linear-gradient(135deg,#178754,#0EA5A4);
                                font-size:1.1rem;
                            ">
                            1
                        </span>

                        <h3 class="mb-0" style="font-size:1.05rem">
                            Pilih Jenis Surat
                        </h3>

                    </div>


                    <div class="card-body">

                        <label class="form-label" style="font-size:.85rem">
                            Kategori
                        </label>

                        <select
                            onchange="pilihKategori(this.value)"
                            class="form-select">

                            <option value="">
                                -- Pilih kategori --
                            </option>

                            <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option
                                    value="<?php echo e($k->id_kategori); ?>"
                                    <?php echo e((string) $kategoriTerpilih === (string) $k->id_kategori ? 'selected' : ''); ?>>

                                    <?php echo e($k->nama_kategori); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                    </div>

                </div>


                
                <?php if($template): ?>

                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex align-items-start gap-3">

                                <div
                                    class="d-inline-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                    style="
                                        width:40px;
                                        height:40px;
                                        background:var(--primary-light);
                                        color:var(--bs-primary);
                                    ">

                                    <i class="bi bi-file-earmark-text"></i>

                                </div>

                                <div>

                                    <p
                                        class="mb-1 text-muted"
                                        style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;">
                                        Template 
                                    </p>

                                    <p
                                        class="mb-0 fw-semibold"
                                        style="font-size:.85rem;color:var(--ink);">

                                        <?php echo e($template->nama_template); ?>


                                    </p>

                                </div>

                            </div>

                        </div>
                    </div>

                <?php elseif($kategoriTerpilih): ?>

                    <div class="alert alert-warning rounded-3 mb-0" style="font-size:.8rem">

                        <div class="d-flex gap-2">

                            <i class="bi bi-exclamation-triangle mt-1"></i>

                            <div>
                                Belum ada template aktif untuk kategori ini.
                                Silakan buat template dari menu
                                <strong>Master Data → Template Surat</strong>.
                            </div>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>
        <div class="col-xl-8 col-xxl-9">

            <?php if($template): ?>

                <?php

                    $dataLama = $suratKeluar?->data_variabel ?? [];

                    $action = $suratKeluar?->exists
                        ? route('surat-keluar.update', $suratKeluar)
                        : route('surat-keluar.store');

                ?>


                <form
                    method="POST"
                    action="<?php echo e($action); ?>">

                    <?php echo csrf_field(); ?>

                    <?php if($suratKeluar?->exists): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <input
                        type="hidden"
                        name="id_template"
                        value="<?php echo e($template->id_template); ?>">

                    <div class="card mb-3">

                        <div
                            class="card-header d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center gap-3">

                                <span
                                    class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                                    style="
                                        width:32px;
                                        height:32px;
                                        background:linear-gradient(135deg,#3E4652,#5B5D6B);
                                        font-size:.85rem;
                                    ">
                                    2
                                </span>

                                <div>

                                    <h3
                                        class="mb-1"
                                        style="font-size:1.05rem">
                                        Isi Data Surat
                                    </h3>

                                    <p
                                        class="text-muted mb-0"
                                        style="font-size:.78rem">

                                        <?php echo e($template->nama_template); ?>


                                    </p>

                                </div>

                            </div>


                            <?php if($suratKeluar?->exists): ?>

                                <span
                                    class="badge rounded-pill text-bg-warning"
                                    style="font-size:.72rem">

                                    Mode revisi

                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Perihal
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="perihal"
                                        value="<?php echo e(old('perihal', $suratKeluar?->perihal)); ?>"
                                        class="form-control"
                                        required>

                                </div>


                                
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Tujuan

                                    </label>

                                    <input
                                        type="text"
                                        name="tujuan"
                                        value="<?php echo e(old('tujuan', $suratKeluar?->tujuan)); ?>"
                                        class="form-control"
                                        placeholder="Instansi / penerima surat">

                                </div>


                                
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Tanggal Surat
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_surat"
                                        value="<?php echo e(old('tanggal_surat', $suratKeluar?->tanggal_surat?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>"
                                        class="form-control"
                                        required>

                                </div>


                                
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Sifat Surat

                                    </label>

                                    <select
                                        name="sifat_surat"
                                        class="form-select">

                                        <?php $__currentLoopData = ['biasa','penting','segera','rahasia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <option
                                                value="<?php echo e($s); ?>"
                                                <?php echo e(old('sifat_surat', $suratKeluar?->sifat_surat ?? 'biasa') === $s ? 'selected' : ''); ?>>

                                                <?php echo e(ucfirst($s)); ?>


                                            </option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="card mb-3">

                        <div class="card-header">

                            <h3
                                class="mb-1"
                                style="font-size:1.05rem">

                                Data Sesuai Template

                            </h3>

                            <p
                                class="text-muted mb-0"
                                style="font-size:.78rem">

                                Field bertanda * wajib diisi.

                            </p>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                <?php $__currentLoopData = $template->variabel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php

                                        $nilai = old(
                                            'variabel_'.$var->id_variabel,
                                            $dataLama[$var->nama_variabel] ?? ''
                                        );

                                    ?>


                                    <div class="col-md-6">

                                        <label
                                            class="form-label"
                                            style="font-size:.85rem">

                                            <?php echo e($var->label); ?>


                                            <?php if($var->wajib): ?>
                                                <span class="text-danger">*</span>
                                            <?php endif; ?>

                                        </label>


                                        
                                        <?php if($var->tipe_input === 'textarea'): ?>

                                            <textarea
                                                name="variabel_<?php echo e($var->id_variabel); ?>"
                                                rows="4"
                                                class="form-control"
                                                <?php echo e($var->wajib ? 'required' : ''); ?>><?php echo e($nilai); ?></textarea>


                                        
                                        <?php elseif($var->tipe_input === 'date'): ?>

                                            <input
                                                type="date"
                                                name="variabel_<?php echo e($var->id_variabel); ?>"
                                                value="<?php echo e($nilai instanceof \Carbon\CarbonInterface ? $nilai->format('Y-m-d') : $nilai); ?>"
                                                class="form-control"
                                                <?php echo e($var->wajib ? 'required' : ''); ?>>


                                        
                                        <?php elseif($var->tipe_input === 'number'): ?>

                                            <input
                                                type="number"
                                                name="variabel_<?php echo e($var->id_variabel); ?>"
                                                value="<?php echo e($nilai); ?>"
                                                class="form-control"
                                                <?php echo e($var->wajib ? 'required' : ''); ?>>


                                        
                                        <?php elseif($var->tipe_input === 'select' && $var->nama_variabel === 'jenis_kelamin'): ?>

                                            <select
                                                name="variabel_<?php echo e($var->id_variabel); ?>"
                                                class="form-select"
                                                <?php echo e($var->wajib ? 'required' : ''); ?>>

                                                <option value="">
                                                    -- Pilih --
                                                </option>

                                                <option
                                                    value="Laki-laki"
                                                    <?php echo e($nilai === 'Laki-laki' ? 'selected' : ''); ?>>

                                                    Laki-laki

                                                </option>

                                                <option
                                                    value="Perempuan"
                                                    <?php echo e($nilai === 'Perempuan' ? 'selected' : ''); ?>>

                                                    Perempuan

                                                </option>

                                            </select>


                                        
                                        <?php else: ?>

                                            <input
                                                type="text"
                                                name="variabel_<?php echo e($var->id_variabel); ?>"
                                                value="<?php echo e($nilai); ?>"
                                                class="form-control"
                                                <?php echo e($var->wajib ? 'required' : ''); ?>>

                                        <?php endif; ?>

                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        </div>

                    </div>
                    <div class="d-flex flex-wrap justify-content-end gap-2">

                        <a
                            href="<?php echo e(route('surat-keluar.index')); ?>"
                            class="btn btn-light">

                            Batal

                        </a>


                        <button
                            type="submit"
                            class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="
                                background:linear-gradient(135deg,#178754,#0EA5A4);
                                border:none;
                            ">

                            <i class="bi bi-save"></i>

                            <?php echo e($suratKeluar?->exists ? 'Simpan Perubahan' : 'Simpan sebagai Draft'); ?>


                        </button>

                    </div>

                </form>


            <?php else: ?>

                
                <div class="card">

                    <div
                        class="card-body d-flex align-items-center justify-content-center text-center"
                        style="min-height:360px">

                        <div>

                            <span
                                class="d-inline-flex align-items-center justify-content-center rounded-3 text-white mx-auto mb-3"
                                style="
                                    width:56px;
                                    height:56px;
                                    background:linear-gradient(135deg,#178754,#0EA5A4);
                                    font-size:1.4rem;
                                ">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <h3
                                class="mb-2"
                                style="font-size:1.15rem">

                                Mulai membuat surat

                            </h3>


                            <p
                                class="text-muted mb-0 mx-auto"
                                style="
                                    font-size:.85rem;
                                    max-width:380px;
                                ">

                                Pilih kategori surat di sebelah kiri.
                                Template akan dipilih otomatis dan
                                form pengisian akan muncul di sini.

                            </p>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>
    <script>

        function pilihKategori(id) {

            const url = new URL(
                '<?php echo e(route('surat-keluar.create')); ?>',
                window.location.origin
            );

            if (id) {
                url.searchParams.set('kategori', id);
            }

            window.location = url.toString();

        }

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\simaster\resources\views/surat-keluar/create.blade.php ENDPATH**/ ?>
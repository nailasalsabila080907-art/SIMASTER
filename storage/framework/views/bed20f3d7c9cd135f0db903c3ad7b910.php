<?php $__env->startSection('title', 'Lembar Disposisi'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .lembar-disposisi {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 35px 40px;
        border: 1px solid #ddd;
    }

    .kop-surat img {
        width: 100%;
        max-height: 180px;
        object-fit: contain;
        display: block;
    }

    .judul-disposisi {
        text-align: center;
        font-weight: 700;
        font-size: 18px;
        margin: 18px 0 20px;
        text-decoration: underline;
    }

    .info-table,
    .disposisi-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td,
    .disposisi-table td,
    .disposisi-table th {
        border: 1px solid #222;
        padding: 9px 10px;
        vertical-align: top;
    }

    .label {
        width: 180px;
        font-weight: 600;
    }

    .isi-disposisi {
        min-height: 150px;
        white-space: pre-line;
    }

    .tombol-atas {
        max-width: 900px;
        margin: 0 auto 15px;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .sidebar,
        .topbar,
        .tombol-atas,
        nav,
        header {
            display: none !important;
        }

        .lembar-disposisi {
            max-width: none;
            border: none;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    }
</style>

<div class="tombol-atas d-flex justify-content-between align-items-center">
    <a href="<?php echo e(url()->previous()); ?>"
       class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

    <button type="button"
            onclick="window.print()"
            class="btn btn-success">
        <i class="bi bi-printer"></i>
        Cetak Lembar Disposisi
    </button>
</div>

<div class="lembar-disposisi">

    
    <?php if($sekolah?->kop_surat_path): ?>

        <div class="kop-surat">
            <img src="<?php echo e(asset('storage/'.$sekolah->kop_surat_path)); ?>"
                 alt="Kop Surat Sekolah">
        </div>

    <?php else: ?>

        <div class="text-center mb-4">
            <h3 class="mb-1"><?php echo e($sekolah->nama_sekolah ?? 'SMK Negeri 7'); ?></h3>
            <p class="mb-0">
                <?php echo e($sekolah->alamat ?? ''); ?>

            </p>
        </div>

    <?php endif; ?>


    <div class="judul-disposisi">
        LEMBAR DISPOSISI
    </div>


    
    <table class="info-table">

        <tr>
            <td class="label">
                Diterima
            </td>
            <td>
                <?php echo e($disposisi->suratMasuk->tanggal_diterima?->format('d/m/Y') ?? '-'); ?>

            </td>

            <td class="label">
                Nomor Agenda
            </td>
            <td>
                <?php echo e($disposisi->suratMasuk->nomor_surat_masuk ?? '-'); ?>

            </td>
        </tr>

        <tr>
            <td class="label">
                Pengirim
            </td>
            <td colspan="3">
                <?php echo e($disposisi->suratMasuk->asal_instansi ?? '-'); ?>

            </td>
        </tr>

        <tr>
            <td class="label">
                Nomor Surat
            </td>
            <td>
                <?php echo e($disposisi->suratMasuk->nomor_surat_asal ?? '-'); ?>

            </td>

            <td class="label">
                Tanggal Surat
            </td>
            <td>
                <?php echo e($disposisi->suratMasuk->tanggal_surat?->format('d/m/Y') ?? '-'); ?>

            </td>
        </tr>

        <tr>
            <td class="label">
                Perihal
            </td>
            <td colspan="3">
                <?php echo e($disposisi->suratMasuk->perihal ?? '-'); ?>

            </td>
        </tr>

        <tr>
            <td class="label">
                Sifat Surat
            </td>
            <td colspan="3">
                <?php echo e(ucfirst($disposisi->suratMasuk->sifat_surat ?? 'Biasa')); ?>

            </td>
        </tr>

    </table>


    <div class="mt-4">

        <table class="disposisi-table">

            <tr>
                <th style="width:35%">
                    DITUJUKAN KEPADA YTH.
                </th>

                <th>
                    ISI DISPOSISI
                </th>
            </tr>

            <tr>

                <td>
                    <strong>
                        <?php echo e($disposisi->tujuan_label); ?>

                    </strong>

                    <br><br>

                    <small>
                        Disposisi dari:
                    </small>

                    <br>

                    <?php echo e($disposisi->pemberiDisposisi->nama_lengkap ?? '-'); ?>


                    <br><br>

                    <small>
                        Tanggal:
                    </small>

                    <br>

                    <?php echo e($disposisi->tanggal_disposisi?->format('d/m/Y H:i') ?? '-'); ?>

                </td>

                <td class="isi-disposisi">

                    <?php if($disposisi->instruksi): ?>
                        <strong>Instruksi:</strong>
                        <br>
                        <?php echo e($disposisi->instruksi); ?>

                    <?php endif; ?>

                    <?php if($disposisi->catatan): ?>
                        <br><br>
                        <strong>Catatan:</strong>
                        <br>
                        <?php echo e($disposisi->catatan); ?>

                    <?php endif; ?>

                </td>

            </tr>

        </table>

    </div>


    <div class="mt-4 text-end">

        <small>
            Status Disposisi:
        </small>

        <strong>
            <?php echo e(ucfirst($disposisi->status)); ?>

        </strong>
    <?php if($disposisi->status === 'ditolak' && $disposisi->alasan_tolak): ?>
    <div class="text-start mt-2">
        <small class="text-muted">Alasan penolakan:</small><br>
        <span><?php echo e($disposisi->alasan_tolak); ?></span>
    </div>
    <?php endif; ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/disposisi/show.blade.php ENDPATH**/ ?>
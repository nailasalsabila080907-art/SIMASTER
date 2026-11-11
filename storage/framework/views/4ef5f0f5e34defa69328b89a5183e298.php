<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            color: #000;
            margin: 30px 40px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 65px;
            height: 65px;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }

        .kop-text p {
            font-size: 10pt;
            margin: 2px 0;
        }

        .nomor {
            text-align: center;
            margin-top: 25px;
            line-height: 1.5;
            text-decoration: underline;
        }

        .isi {
            line-height: 1.5;
        }

        .isi p {
            margin: 12px 0;
            text-align: justify;
        }
    </style>
</head>

<body>

<?php
    $sekolah = \App\Models\Sekolah::first();

    $logoBase64 = null;

    if ($sekolah?->logo_path) {
        $logoPath = storage_path('app/public/' . $sekolah->logo_path);

        if (file_exists($logoPath)) {
            $mime = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    }
?>

<div class="kop">

    <table class="kop-table">
        <tr>

            <?php if($logoBase64): ?>
                <td class="kop-logo">
                    <img src="<?php echo e($logoBase64); ?>" alt="Logo Sekolah">
                </td>
            <?php endif; ?>

            <td class="kop-text">

                <h1>Pemerintah Provinsi Riau</h1>

                <h1>Dinas Pendidikan</h1>

                <h1>
                    <?php echo e($sekolah->nama_sekolah ?? 'SMK Negeri 7 Pekanbaru'); ?>

                </h1>

                <p>
                    <?php echo e($sekolah->alamat ?? '[Alamat Sekolah]'); ?>

                    &middot;
                    <?php echo e($sekolah->kota ?? 'Pekanbaru'); ?>,
                    <?php echo e($sekolah->provinsi ?? 'Riau'); ?>

                    &middot;
                    Telp: <?php echo e($sekolah->telepon ?? '-'); ?>

                    &middot;
                    Email: <?php echo e($sekolah->email ?? '-'); ?>

                </p>

            </td>

        </tr>
    </table>

</div>

<p class="nomor">
    Nomor: <?php echo e($suratKeluar->nomor_surat); ?><br>
    Perihal: <?php echo e($suratKeluar->perihal); ?>

</p>

<div class="isi">
    <?php echo $suratKeluar->isi_surat_cetak; ?>

</div>

</body>
</html><?php /**PATH C:\xampp\htdocs\SIMASTER\resources\views/pdf/surat-keluar.blade.php ENDPATH**/ ?>
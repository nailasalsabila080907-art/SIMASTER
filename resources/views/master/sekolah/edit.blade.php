@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')

<div class="mb-4">
    <h5 class="mb-0" style="color:var(--ink)">Profil Sekolah</h5>
    <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
        Kelola identitas sekolah untuk kop surat dan dokumen resmi.
    </p>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-body p-4">

        @if(session('sukses'))
            <div class="alert d-flex align-items-center gap-2 border-0 mb-4"
                 style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;font-size:.85rem;">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('sukses') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert d-flex align-items-start gap-2 border-0 mb-4"
                 style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                <i class="bi bi-exclamation-circle-fill mt-1"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST"
              action="{{ route('sekolah.update') }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <p class="text-uppercase mb-3"
               style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-image"></i> Logo Sekolah
            </p>

            <div class="d-flex align-items-center gap-3 mb-4">

                <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:80px;height:80px;border-radius:10px;background:var(--surface);border:1px solid var(--border);overflow:hidden;">

                    @if($sekolah->logo_path)
                        <img src="{{ asset('storage/'.$sekolah->logo_path) }}"
                             alt="Logo Sekolah"
                             class="w-100 h-100"
                             style="object-fit:contain;padding:.4rem;">
                    @else
                        <i class="bi bi-building"
                           style="font-size:1.5rem;color:var(--ink-muted);"></i>
                    @endif

                </div>

                <div class="flex-fill">
                    <input type="file"
                           name="logo"
                           accept="image/png,image/jpeg,image/jpg,image/webp"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">

                    <p class="mb-0 mt-1"
                       style="color:var(--ink-muted);font-size:.76rem;">
                        Format PNG, JPG, JPEG, atau WEBP. Maksimal 2 MB.
                    </p>
                </div>

            </div>

            <hr class="my-4" style="border-color:var(--border);">

            <p class="text-uppercase mb-3"
               style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-file-earmark-image"></i> Kop Surat
            </p>

            @if($sekolah->kop_surat_path)
                <div class="mb-3">

                    <div class="border rounded-3 p-2"
                         style="background:#fff;border-color:var(--border)!important;">

                        <img src="{{ asset('storage/'.$sekolah->kop_surat_path) }}"
                             alt="Kop Surat"
                             class="img-fluid"
                             style="width:100%;max-height:220px;object-fit:contain;">

                    </div>

                    <div class="mt-2">
                        <a href="{{ asset('storage/'.$sekolah->kop_surat_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-light"
                           style="border:1px solid var(--border);border-radius:8px;">
                            <i class="bi bi-eye"></i>
                            Lihat Kop Surat
                        </a>
                    </div>

                </div>
            @endif

            <div class="mb-4">

                <input type="file"
                       name="kop_surat"
                       accept="image/png,image/jpeg,image/jpg,image/webp"
                       class="form-control"
                       style="border-radius:10px;border-color:var(--border);font-size:.87rem;">

                <p class="mb-0 mt-1"
                   style="color:var(--ink-muted);font-size:.76rem;">
                    Upload gambar kop surat resmi sekolah. Format PNG, JPG, JPEG, atau WEBP. Maksimal 4 MB.
                </p>

            </div>

            <hr class="my-4" style="border-color:var(--border);">

            <p class="text-uppercase mb-3"
               style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-building"></i> Identitas Sekolah
            </p>

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Nama Sekolah
                    </label>

                    <input type="text"
                           name="nama_sekolah"
                           value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                           required>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Alamat
                    </label>

                    <textarea name="alamat"
                              rows="3"
                              class="form-control"
                              style="border-radius:10px;border-color:var(--border);font-size:.87rem;">{{ old('alamat', $sekolah->alamat) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Kota
                    </label>

                    <input type="text"
                           name="kota"
                           value="{{ old('kota', $sekolah->kota) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Provinsi
                    </label>

                    <input type="text"
                           name="provinsi"
                           value="{{ old('provinsi', $sekolah->provinsi) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Kode Pos
                    </label>

                    <input type="text"
                           name="kode_pos"
                           value="{{ old('kode_pos', $sekolah->kode_pos) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Telepon
                    </label>

                    <input type="text"
                           name="telepon"
                           value="{{ old('telepon', $sekolah->telepon) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $sekolah->email) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Website
                    </label>

                    <input type="text"
                           name="website"
                           value="{{ old('website', $sekolah->website) }}"
                           class="form-control"
                           placeholder="https://..."
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

            </div>

            <hr class="my-4" style="border-color:var(--border);">

            <p class="text-uppercase mb-3"
               style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-person-vcard"></i> Kepala Sekolah
            </p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        Nama Kepala Sekolah
                    </label>

                    <input type="text"
                           name="nama_kepala_sekolah"
                           value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">
                        NIP Kepala Sekolah
                    </label>

                    <input type="text"
                           name="nip_kepala_sekolah"
                           value="{{ old('nip_kepala_sekolah', $sekolah->nip_kepala_sekolah) }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                </div>

            </div>

            <div class="d-flex gap-2 mt-4 pt-3"
                 style="border-top:1px solid var(--border);">

                <button type="submit"
                        class="btn text-white"
                        style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                    <i class="bi bi-check2"></i>
                    Simpan
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
@extends('layouts.app')

@section('title', $jabatan->exists ? 'Ubah Jabatan' : 'Tambah Jabatan')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('jabatan.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                {{ $jabatan->exists ? 'Ubah Jabatan' : 'Tambah Jabatan' }}
            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Lengkapi data jabatan di bawah ini.
            </p>
        </div>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-body p-4">

            @if($errors->any())
                <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                    <i class="bi bi-exclamation-circle-fill mt-1"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ $jabatan->exists ? route('jabatan.update', $jabatan) : route('jabatan.store') }}">
                @csrf
                @if($jabatan->exists) @method('PUT') @endif

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-person-badge"></i> Data Jabatan
                </p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                               placeholder="mis. Kepala Sekolah" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Level (untuk urutan approval)</label>
                        <input type="number" name="level_jabatan" value="{{ old('level_jabatan', $jabatan->level_jabatan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                               placeholder="mis. 1, 2, 3 - makin besar makin tinggi" min="1" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Keterangan (opsional)</label>
                        <textarea name="keterangan" rows="3" class="form-control"
                                  style="border-radius:10px;border-color:var(--border);font-size:.87rem;">{{ old('keterangan', $jabatan->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="{{ route('jabatan.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

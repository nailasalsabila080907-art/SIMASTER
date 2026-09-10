@extends('layouts.app')

@section('title', $unit->exists ? 'Ubah Unit Kerja' : 'Tambah Unit Kerja')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('unit-kerja.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                {{ $unit->exists ? 'Ubah Unit Kerja' : 'Tambah Unit Kerja' }}
            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Lengkapi data unit kerja di bawah ini.
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

            <form method="POST" action="{{ $unit->exists ? route('unit-kerja.update', $unit) : route('unit-kerja.store') }}">
                @csrf
                @if($unit->exists) @method('PUT') @endif

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-diagram-3"></i> Data Unit Kerja
                </p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kode Unit</label>
                        <input type="text" name="kode_unit" value="{{ old('kode_unit', $unit->kode_unit) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Unit</label>
                        <input type="text" name="nama_unit" value="{{ old('nama_unit', $unit->nama_unit) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="aktif" {{ old('status', $unit->status ?: 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $unit->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan', $unit->keterangan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="{{ route('unit-kerja.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

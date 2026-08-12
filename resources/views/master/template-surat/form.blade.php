@extends('layouts.app')

@section('title', $template->exists ? 'Kelola Template' : 'Tambah Template')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('template-surat.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                {{ $template->exists ? 'Kelola Template' : 'Tambah Template' }}
            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Atur isi, format nomor, dan field dinamis template surat.
            </p>
        </div>
    </div>

    @if(session('sukses'))
        <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#EAF7EE;color:#2E7D4F;border-radius:12px;font-size:.85rem;">
            <i class="bi bi-check-circle-fill mt-1"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    <div class="card" style="max-width:900px;">
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

            <form method="POST" action="{{ $template->exists ? route('template-surat.update', $template) : route('template-surat.store') }}">
                @csrf
                @if($template->exists) @method('PUT') @endif

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-file-earmark-text"></i> Info Template
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Template</label>
                        <input type="text" name="nama_template" value="{{ old('nama_template', $template->nama_template) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kode Template</label>
                        <input type="text" name="kode_template" value="{{ old('kode_template', $template->kode_template) }}" placeholder="mis. TPL-CUTI-01"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select name="id_kategori" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                            <option value="">-- Pilih kategori --</option>
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->id_kategori }}" {{ (string) old('id_kategori', $template->id_kategori) === (string) $k->id_kategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Format Nomor Surat</label>
                        <input type="text" name="format_nomor" value="{{ old('format_nomor', $template->format_nomor ?: '420.5/SMKN-07/KP/{tahun}/{no_urut}') }}"
                               class="form-control font-monospace" style="border-radius:10px;border-color:var(--border);font-size:.85rem;" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Isi Template (HTML, gunakan placeholder seperti nama_field)</label>
                        <textarea name="isi_template" rows="10" class="form-control font-monospace"
                                  style="border-radius:10px;border-color:var(--border);font-size:.85rem;">{{ old('isi_template', $template->isi_template) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2" style="background:var(--bs-light,#f8f9fa);">
                            <input type="hidden" name="is_active" value="0">
                            <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input mt-0"
                                   {{ old('is_active', $template->exists ? $template->is_active : true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label small mb-0">Template aktif dan dapat dipilih saat membuat surat</label>
                        </div>
                    </div>
                </div>

                @if($template->exists)
                    <hr class="my-4" style="border-color:var(--border);">

                    <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                        <i class="bi bi-list-check"></i> Field Dinamis yang Sudah Ada
                    </p>

                    <div class="d-flex flex-column gap-2 mb-3">
                        @forelse($template->variabel as $v)
                            <div class="d-flex align-items-center justify-content-between rounded-3 px-3 py-2" style="background:var(--bs-light,#f8f9fa);font-size:.85rem;">
                                <span>
                                    {{ $v->label }}
                                    <span class="font-monospace" style="font-size:.75rem;color:var(--ink-muted);">
                                        &#123;&#123;{{ $v->nama_variabel }}&#125;&#125;
                                    </span>
                                    &middot; {{ $v->tipe_input }}
                                </span>
                                <a href="{{ route('template-surat.variabel.hapus', $v) }}"
                                   onclick="event.preventDefault(); document.getElementById('hapus-var-{{ $v->id_variabel }}').submit();"
                                   class="text-danger small">
                                    <i class="bi bi-trash3"></i> Hapus
                                </a>
                                <form id="hapus-var-{{ $v->id_variabel }}" method="POST" action="{{ route('template-surat.variabel.hapus', $v) }}" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @empty
                            <p class="small mb-0" style="color:var(--ink-muted)">Belum ada field.</p>
                        @endforelse
                    </div>

                    <hr class="my-4" style="border-color:var(--border);">

                    <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                        <i class="bi bi-plus-circle"></i> Tambah Field Baru
                    </p>
                    <div id="field-baru-container" class="d-flex flex-column gap-2 mb-2"></div>
                    <button type="button" onclick="tambahBarisField()" class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;font-weight:600;font-size:.8rem;color:var(--bs-primary);">
                        <i class="bi bi-plus-lg"></i> Tambah Baris Field
                    </button>
                @endif

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="{{ route('template-surat.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Selesai
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tambahBarisField() {
            const container = document.getElementById('field-baru-container');
            const div = document.createElement('div');
            div.className = 'row g-2';
            div.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="variabel_baru_nama[]" placeholder="nama_field" class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
                </div>
                <div class="col-md-4">
                    <input type="text" name="variabel_baru_label[]" placeholder="Label tampilan" class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
                </div>
                <div class="col-md-4">
                    <select name="variabel_baru_tipe[]" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="date">Tanggal</option>
                        <option value="number">Angka</option>
                        <option value="select">Pilihan</option>
                    </select>
                </div>
            `;
            container.appendChild(div);
        }
    </script>

@endsection

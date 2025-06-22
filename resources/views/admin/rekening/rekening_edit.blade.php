@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Rekening</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                {{-- KIRI: FORM EDIT --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <h4 class="header-title">Edit Rekening</h4>
                            <p class="card-title-desc"><code>Perhatikan Tulisan Dengan Baik dan Benar</code></p>
                            
                            {{-- Form mengarah ke method UPDATE --}}
                            <form action="{{ route('rekening.update', $edit_rek->id_rekening) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') {{-- Method untuk update --}}
                                
                                {{-- NAMA BANK --}}
                                <div class="mb-3">
                                    <label for="nama_rek" class="form-label">Jenis Rekening (Nama Bank)</label>
                                    <input
                                        class="form-control @error('nama_rek') is-invalid @enderror"
                                        name="nama_rek" type="text" placeholder="Contoh: BCA, BNI"
                                        value="{{ old('nama_rek', $edit_rek->nama_rek) }}" required>
                                    @error('nama_rek')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                {{-- NOMOR REKENING --}}
                                <div class="mb-3">
                                    <label for="no_rek" class="form-label">Nomor Rekening</label>
                                    <input
                                        class="form-control @error('no_rek') is-invalid @enderror"
                                        name="no_rek" type="number" placeholder="Masukan Nomor Rekening"
                                        value="{{ old('no_rek', $edit_rek->no_rek) }}" required>
                                    @error('no_rek')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                {{-- =============================================== --}}
                                {{-- PERUBAHAN 1: TAMBAHKAN INPUT "ATAS NAMA" --}}
                                <div class="mb-3">
                                    <label for="atas_nama" class="form-label">Atas Nama</label>
                                    <input
                                        class="form-control @error('atas_nama') is-invalid @enderror"
                                        name="atas_nama" type="text" placeholder="Masukan Nama Pemilik Rekening"
                                        value="{{ old('atas_nama', $edit_rek->atas_nama) }}" required>
                                    @error('atas_nama')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- =============================================== --}}
                                
                                {{-- =============================================== --}}
                                {{-- PERUBAHAN 2: TAMBAHKAN INPUT "LOGO" --}}
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Ganti Logo Bank</label>
                                    {{-- Tampilkan logo yang sekarang ada --}}
                                    @if($edit_rek->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('images/bank/' . $edit_rek->logo) }}" alt="Logo saat ini" style="width: 100px; background-color: #f8f9fa; padding: 5px; border-radius: 5px;">
                                        <small class="d-block mt-1">Logo saat ini</small>
                                    </div>
                                    @endif

                                    <input
                                        class="form-control @error('logo') is-invalid @enderror"
                                        name="logo" type="file" accept="image/png, image/jpeg, image/svg+xml">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah logo.</small>
                                    @error('logo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- =============================================== --}}

                                <button type="submit"
                                    class="btn btn-success waves-effect waves-light w-100">Simpan Perubahan</button>
                                <a href="{{ route('rekening.index') }}" class="btn btn-light waves-effect waves-light w-100 mt-2">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KANAN: TABEL REFERENSI (Sama seperti halaman index) --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Rekening</h4>
                            <p class="card-title-desc">Data Rekening Pembayaran Produk</p>

                            <div class="table-responsive">
                                <table class="table table-bordered border-primary mb-0">
                                    <thead>
                                        <tr>
                                            <th>Jenis Rekening</th>
                                            <th>Nomor Rekening</th>
                                            <th>Atas Nama</th>
                                            <th>Logo</th>
                                            {{-- Action tidak perlu di tabel referensi ini --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rekening as $hasil)
                                            <tr>
                                                <td><b>{{ Str::upper($hasil->nama_rek) }}</b></td>
                                                <td><i>{{ $hasil->no_rek }}</i></td>
                                                <td>{{ Str::title($hasil->atas_nama) }}</td>
                                                <td>
                                                    @if($hasil->logo)
                                                        <img src="{{ asset('images/bank/' . $hasil->logo) }}" alt="Logo" style="width: 80px;">
                                                    @else
                                                        No Logo
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-2">
                                {{ $rekening->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> @endsection
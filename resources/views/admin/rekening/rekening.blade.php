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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <h4 class="header-title">Tambah Rekening Baru</h4>
                            <p class="card-title-desc"><code>Perhatikan Tulisan Dengan Baik dan Benar</code></p>
                            
                            {{-- PASTIKAN FORM MEMILIKI ENCTYPE UNTUK UPLOAD FILE --}}
                            <form action="{{ Route('rekening.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                {{-- INPUT 1: NAMA BANK --}}
                                <div class="mb-3">
                                    <label for="nama_rek" class="form-label">Jenis Rekening (Nama Bank)</label>
                                    <input
                                        class="form-control @error('nama_rek') is-invalid @enderror"
                                        name="nama_rek" type="text" placeholder="Contoh: BCA, BNI, DANA"
                                        value="{{ old('nama_rek') }}" required>
                                    @error('nama_rek')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                {{-- INPUT 2: NOMOR REKENING --}}
                                <div class="mb-3">
                                    <label for="no_rek" class="form-label">Nomor Rekening</label>
                                    <input
                                        class="form-control @error('no_rek') is-invalid @enderror"
                                        name="no_rek" type="number" placeholder="Masukan Nomor Rekening"
                                        value="{{ old('no_rek') }}" required>
                                    @error('no_rek')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                {{-- ======================================================= --}}
                                {{-- PERUBAHAN 1: TAMBAHKAN INPUT UNTUK "ATAS NAMA" --}}
                                <div class="mb-3">
                                    <label for="atas_nama" class="form-label">Atas Nama</label>
                                    <input
                                        class="form-control @error('atas_nama') is-invalid @enderror"
                                        name="atas_nama" type="text" placeholder="Masukan Nama Pemilik Rekening"
                                        value="{{ old('atas_nama') }}" required>
                                    @error('atas_nama')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- ======================================================= --}}
                                
                                {{-- ======================================================= --}}
                                {{-- PERUBAHAN 2: TAMBAHKAN INPUT UNTUK UPLOAD LOGO --}}
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo Bank</label>
                                    <input
                                        class="form-control @error('logo') is-invalid @enderror"
                                        name="logo" type="file" accept="image/png, image/jpeg, image/svg+xml">
                                    @error('logo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- ======================================================= --}}

                                <button type="submit"
                                    class="btn btn-success waves-effect waves-light w-100">Simpan Rekening</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @if (session('delete'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('delete') }}
                                </div>
                            @endif
                            <h4 class="header-title">Data Rekening</h4>
                            <p class="card-title-desc">Data Rekening Pembayaran Produk</p>

                            <div class="table-responsive">
                                <table class="table table-bordered border-primary mb-0">
                                    <thead>
                                        <tr>
                                            <th>Jenis Rekening</th>
                                            <th>Nomor Rekening</th>
                                            {{-- PERUBAHAN 3: TAMBAHKAN KOLOM "ATAS NAMA" & "LOGO" --}}
                                            <th>Atas Nama</th>
                                            <th>Logo</th>
                                            <th><center>Action</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rekening as $hasil)
                                            <tr>
                                                <td><b>{{ Str::upper($hasil->nama_rek) }}</b></td>
                                                <td><i>{{ $hasil->no_rek }}</i></td>
                                                
                                                {{-- PERUBAHAN 4: TAMPILKAN DATA "ATAS NAMA" & "LOGO" --}}
                                                <td>{{ Str::title($hasil->atas_nama) }}</td>
                                                <td>
                                                    @if($hasil->logo)
                                                        <img src="{{ asset('images/bank/' . $hasil->logo) }}" alt="Logo" style="width: 80px;">
                                                    @else
                                                        No Logo
                                                    @endif
                                                </td>

                                                <td align="center">
                                                    <a href="{{ route('rekening.edit', $hasil->id_rekening) }}"
                                                        class="btn btn-warning waves-effect waves-light"><i
                                                            class="dripicons-pencil"></i> Edit</a>
                                                    <form action="{{ route('rekening.destroy', $hasil->id_rekening) }}" method="POST"
                                                        style="display:inline"
                                                        onsubmit="return confirm('Apakah Yakin akan Di Hapus ?');">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger waves-effect waves-light"><i
                                                                class="dripicons-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-felx justify-content-center mt-2">
                                {{ $rekening->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> @endsection

@section('js')
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2500);
    </script>
@endsection

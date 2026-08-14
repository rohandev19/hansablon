@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Produk</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success mb-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('delete'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('delete') }}
                                </div>
                            @endif
                            <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3"><i
                                    class="mdi mdi-store-plus"></i> Tambah Produk Baru</a>
                            <div class="mt-3">
                                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Stok</th> {{-- KOLOM BARU --}}
                                            <th class="text-center">Foto</th>
                                            <th>Tgl Upload</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produk as $data)
                                            <tr>
                                                <td><b>{{ Str::upper($data->nama_produk) }}</b></td>
                                                <td><i>{{ Str::upper($data->kategoriData->jenis_kategori ?? '') }}</i></td>
                                                {{-- DATA STOK DITAMPILKAN --}}
                                                <td>
                                                    <span class="badge bg-info p-2">{{ $data->stok }} pcs</span>
                                                </td>
                                                <td class="text-center">
                                                    <img src="/produk/{{ $data->foto_produk1 }}" alt=""
                                                        height="30px" width="30px">
                                                </td>
                                                <td>{{ $data->created_at->format('d M Y, H:i') }}</td>
                                                <td class="text-center">
                                                    {{-- TOMBOL BARU UNTUK TAMBAH STOK --}}
                                                    <button type="button" class="btn btn-success btn-sm waves-effect waves-light tambah-stok-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#tambahStokModal" 
                                                        data-id="{{ $data->id_produk }}" 
                                                        data-nama="{{ $data->nama_produk }}">
                                                        <i class="mdi mdi-plus-box"></i> Stok
                                                    </button>

                                                    <a href="{{ route('produk.edit', $data->id_produk) }}"
                                                        class="btn btn-warning btn-sm waves-effect waves-light"><i
                                                            class="dripicons-pencil"></i> Edit</a>
                                                    <form action="{{ route('produk.destroy', $data->id_produk) }}"
                                                        method="POST" style="display:inline"
                                                        onsubmit="return confirm('Apakah Yakin akan Di Hapus ?');">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm waves-effect waves-light"><i
                                                                class="dripicons-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <div class="modal fade" id="tambahStokModal" tabindex="-1" aria-labelledby="tambahStokModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahStokModalLabel">Tambah Stok Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tambahStokForm" method="POST"> {{-- Action akan diisi oleh Javascript --}}
                    @csrf
                    <div class="modal-body">
                        <p>Produk: <strong id="namaProdukModal"></strong></p>
                        <div class="mb-3">
                            <label for="jumlah_tambah" class="form-label">Jumlah Stok yang Ditambahkan</label>
                            <input type="number" class="form-control" id="jumlah_tambah" name="jumlah_tambah" required min="1" placeholder="Masukkan jumlah...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


{{-- SECTION CSS DAN JS BAWAAN ANDA TIDAK PERLU DIUBAH --}}
@section('css')
    <link href="/morvin/dist/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/morvin/dist/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/morvin/dist/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="/morvin/dist/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/morvin/dist/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    
    {{-- ... (semua script datatables Anda yang lain) ... --}}
    
    <script src="/morvin/dist/assets/js/pages/datatables.init.js"></script>

    {{-- SCRIPT BARU UNTUK MODAL --}}
    <script>
        // Script untuk auto-hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2500);

        // Script untuk handle klik tombol "Tambah Stok"
        $(document).ready(function() {
            $('.tambah-stok-btn').on('click', function() {
                // Ambil data dari tombol yang diklik
                var productId = $(this).data('id');
                var productName = $(this).data('nama');
                
                // Set nama produk di modal
                $('#namaProdukModal').text(productName);
                
                // Set action form di modal
                var url = "{{ url('admin/produk') }}/" + productId + "/tambah-stok";
                $('#tambahStokForm').attr('action', url);
            });
        });
    </script>
@endsection

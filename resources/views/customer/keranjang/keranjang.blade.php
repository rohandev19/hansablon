@extends('customer.layouts.master')

@section('content')
    {{-- Fungsi helper diletakkan di luar loop agar lebih efisien --}}
        @php
            function formatRupiah($angka)
            {
                if ($angka === null)
                    return 'N/A';
                return 'Rp ' . number_format($angka, 2, ',', '.');
            }
        @endphp

        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Keranjang</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Keranjang</li>
                            </ol>
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
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @elseif (session('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-centered mb-0 table-nowrap">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width: 120px">Foto Produk</th>
                                                <th>Nama Produk</th>
                                                <th>Harga Produk</th>
                                                <th>Jumlah Pembelian</th>
                                                <th>Total Pembayaran</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($keranjang as $item)
                                                <tr>
                                                    <td>
                                                        <img src="/produk/{{ $item->foto_produk1 }}" alt="product-img"
                                                            title="product-img" class="avatar-md" />
                                                    </td>
                                                    <td>
                                                        <h5 class="font-size-14 text-truncate">
                                                            <a href="{{ route('customer.detail_produk', $item->id_produk) }}" class="text-reset">{{ Str::upper($item->nama_produk) }}</a>
                                                        </h5>
                                                        <p class="mb-0">Color : <span class="font-weight-medium">All Variant</span></p>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $hargaSatuan = 0;
                                                            if ($item->total <= 11) {
                                                                $hargaSatuan = $item->harga_produk1;
                                                            } elseif ($item->total <= 23) {
                                                                $hargaSatuan = $item->harga_produk2;
                                                            } elseif ($item->total <= 50) {
                                                                $hargaSatuan = $item->harga_produk3;
                                                            } elseif ($item->total <= 100) {
                                                                $hargaSatuan = $item->harga_produk4;
                                                            } else {
                                                                $hargaSatuan = $item->harga_produk5;
                                                            }
                                                            echo formatRupiah($hargaSatuan);
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        <div style="width: 150px;" class="product-cart-touchspin">
                                                            <form action="{{ route('keranjang.update', $item->id_keranjang) }}" method="post">
                                                                @csrf
                                                                @method('put')

                                                                {{-- INI BAGIAN YANG DIPERBAIKI --}}
                                                                <input data-toggle="touchspin" type="text" name="pembelian"
                                                                    value="{{ $item->total }}"
                                                                    data-bts-min="6"
                                                                    data-bts-max="{{ min(200, $item->stok) }}"
                                                                    data-bts-button-down-class="btn btn-default"
                                                                    data-bts-button-up-class="btn btn-default"
                                                                    >

                                                                <button type="submit" class="form-control btn btn-success py-1 mt-1">Perbaharui</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ formatRupiah($hargaSatuan * $item->total) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('keranjang.show', $item->id_keranjang) }}" class="btn btn-success btn-sm waves-effect waves-light">
                                                            <i class="mdi mdi-cart-variant"></i> Checkout
                                                        </a>
                                                        <form action="{{ route('keranjang.destroy', $item->id_keranjang) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Yakin akan Di Hapus ?');">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">
                                                                <i class="mdi mdi-trash-can"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Keranjang Anda masih kosong.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('css')
    <link href="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
@endsection

@section('js')
    <script src="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    {{-- Gunakan init bawaan jika ada, atau panggil manual jika tidak ada --}}
    <script>
        // Inisialisasi manual untuk setiap input touchspin di dalam tabel
        $(document).ready(function() {
            $("input[name='pembelian']").each(function() {
                $(this).TouchSpin({
                    min: $(this).data('bts-min'),
                    max: $(this).data('bts-max'),
                    buttondown_class: $(this).data('bts-button-down-class'),
                    buttonup_class: $(this).data('bts-button-up-class')
                });
            });
        });

        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 3000);
    </script>
@endsection
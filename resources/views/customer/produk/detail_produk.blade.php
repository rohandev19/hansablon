@extends('customer.layouts.master')

@section('css')
    {{-- CSS untuk komponen TouchSpin (quantity stepper) --}}
    <link href="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <style>
        /* CSS Tambahan untuk Tampilan Stok Baru */
        .stock-display {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    @php
        // Fungsi helper untuk format Rupiah
        function formatRupiah($angka)
        {
            if ($angka === null)
                return 'N/A';
            return 'Rp ' . number_format($angka, 0, ',', '.');
        }
    @endphp

    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Product Details</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Details</li>
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
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-xl-5">
                                    <div class="product-detail">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                    aria-orientation="vertical">
                                                    <a class="nav-link active" id="product-1-tab" data-bs-toggle="pill"
                                                        href="#product-1" role="tab">
                                                        <img src="{{ asset('produk/' . $produk->foto_produk1) }}" alt=""
                                                            class="img-fluid mx-auto d-block tab-img rounded">
                                                    </a>
                                                    <a class="nav-link" id="product-2-tab" data-bs-toggle="pill"
                                                        href="#product-2" role="tab">
                                                        <img src="{{ asset('produk/' . $produk->foto_produk2) }}" alt=""
                                                            class="img-fluid mx-auto d-block tab-img rounded">
                                                    </a>
                                                    <a class="nav-link" id="product-3-tab" data-bs-toggle="pill"
                                                        href="#product-3" role="tab">
                                                        <img src="{{ asset('produk/' . $produk->foto_produk3) }}" alt=""
                                                            class="img-fluid mx-auto d-block tab-img rounded">
                                                    </a>
                                                    <a class="nav-link" id="product-4-tab" data-bs-toggle="pill"
                                                        href="#product-4" role="tab">
                                                        <img src="{{ asset('produk/' . $produk->foto_produk4) }}" alt=""
                                                            class="img-fluid mx-auto d-block tab-img rounded">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-9">
                                                <div class="tab-content" id="v-pills-tabContent">
                                                    <div class="tab-pane fade show active" id="product-1" role="tabpanel">
                                                        <div class="product-img">
                                                            <img src="{{ asset('produk/' . $produk->foto_produk1) }}" alt=""
                                                                class="img-fluid mx-auto d-block">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="product-2" role="tabpanel">
                                                        <div class="product-img">
                                                            <img src="{{ asset('produk/' . $produk->foto_produk2) }}" alt=""
                                                                class="img-fluid mx-auto d-block">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="product-3" role="tabpanel">
                                                        <div class="product-img">
                                                            <img src="{{ asset('produk/' . $produk->foto_produk3) }}" alt=""
                                                                class="img-fluid mx-auto d-block">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="product-4" role="tabpanel">
                                                        <div class="product-img">
                                                            <img src="{{ asset('produk/' . $produk->foto_produk4) }}" alt=""
                                                                class="img-fluid mx-auto d-block">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7">
                                    <div class="mt-4 mt-xl-3">
                                        <a href="#" class="text-primary">{{ Str::upper($produk->jenis_kategori) }}</a>
                                        <h5 class="mt-1 mb-3">{{ Str::title($produk->nama_produk) }}</h5>
                                        <h5 class="mt-2">{{ formatRupiah($produk->harga_produk1) }}</h5>
                                        <hr class="my-4">
                                        <div class="mt-4">
                                            <h6>List Harga</h6>
                                            <div class="mt-4">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>6-11 pcs</th>
                                                            <th>12-23 pcs</th>
                                                            <th>24-50 pcs</th>
                                                            <th>51-100 pcs</th>
                                                            <th>101-200 pcs</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ formatRupiah($produk->harga_produk1) }}</td>
                                                            <td>{{ formatRupiah($produk->harga_produk2) }}</td>
                                                            <td>{{ formatRupiah($produk->harga_produk3) }}</td>
                                                            <td>{{ formatRupiah($produk->harga_produk4) }}</td>
                                                            <td>{{ formatRupiah($produk->harga_produk5) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        @if ($produk->stok > 0)
                                            <form action="{{ route('keranjang.store') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mt-4">
                                                    <h6>Warna & Ukuran :</h6>
                                                    <div class="mt-3">
                                                        <p class="text-muted mb-2"><i
                                                                class="mdi mdi-check-bold text-success me-2"></i>Tersedia
                                                            Seluruh Warna</p>
                                                        <p class="text-muted mb-2"><i
                                                                class="mdi mdi-check-bold text-success me-2"></i>Tersedia
                                                            Seluruh Ukuran Pakaian</p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label">Masukan Jumlah Pembelian</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="col-4 col-md-3">
                                                            <input id="demo0" type="text" value="6" name="demo0"
                                                                data-bts-min="6" data-bts-max="{{ min(200, $produk->stok) }}"
                                                                data-bts-step="1" data-bts-button-down-class="btn btn-default"
                                                                data-bts-button-up-class="btn btn-default">
                                                        </div>

                                                        <div class="ms-3 stock-display">
                                                            @if($produk->stok > 50)
                                                                <span class="text-success"><i
                                                                        class="mdi mdi-check-circle-outline me-1"></i>Stok Tersedia
                                                                    ({{ $produk->stok }} pcs)</span>
                                                            @elseif($produk->stok > 10 && $produk->stok <= 50)
                                                                <span class="text-warning"><i
                                                                        class="mdi mdi-clock-alert-outline me-1"></i>Stok Terbatas
                                                                    ({{ $produk->stok }} pcs)</span>
                                                            @else
                                                                <span class="text-danger fw-bold"><i
                                                                        class="mdi mdi-fire me-1"></i>Stok Hampir Habis! Tinggal
                                                                    {{ $produk->stok }} pcs</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="produk" value="{{ $produk->id_produk }}">
                                                <div class="mt-4">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">
                                                        <i class="mdi mdi-cart me-2"></i> Masukan Keranjang
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="mt-4">
                                                <div class="stock-display mb-3">
                                                    <span class="badge bg-danger fs-6 p-2">STOK HABIS</span>
                                                </div>
                                                <p class="text-muted">Produk ini sudah tidak tersedia saat ini.</p>
                                                <button type="button" class="btn btn-primary waves-effect waves-light mt-2"
                                                    disabled>
                                                    <i class="mdi mdi-cart-off me-2"></i> Masukan Keranjang
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi dan Review --}}
                    <div class="card">
                        <div class="card-body">
                            {{-- ... (kode deskripsi dan review tidak berubah) ... --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/morvin/dist/assets/js/pages/form-advanced.init.js"></script>
@endsection

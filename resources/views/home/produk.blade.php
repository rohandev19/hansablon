@extends('home.layout.master')

@section('content')

    <!-- PRODUCT-AREA START -->
    <div class="product-area pt-80 pb-80 product-style-2" style="background-color: #f4f4f4;">
        <div class="container">
            <div class="row">
                <!-- Sidebar with Categories -->
                <div class="col-lg-3 order-2 order-lg-1 mb-4 mb-lg-0">
                    <!-- Widget-Categories start -->
                    <aside class="widget widget-categories  mb-30">
                        <div class="widget-title">
                            <h4 class="text-primary">Kategori Produk</h4>
                        </div>
                        <div id="cat-treeview" class="widget-info product-cat boxscrol2">
                            <ul>
                                <li class="open"><span>List Kategori Produk</span>
                                    <ul>
                                        @foreach ($kategori as $kategori)
                                            <li><a
                                                    href="{{ route('cari_kategori', $kategori->id_kategori) }}">{{ $kategori->jenis_kategori }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </aside>
                    <!-- Widget-categories end -->
                </div>

                <!-- Products Area -->
                <div class="col-lg-9 order-1 order-lg-2">
                    <!-- Shop-Content Start -->
                    <div class="shop-content mt-tab-30 mb-30 mb-lg-0">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="grid-view">
                                <div class="row">
                                    <!-- Single-product start -->
                                    @php
                                        function rupiah($angka)
                                        {
                                            $hasil_rupiah = 'Rp ' . number_format($angka, 2, ',', '.');
                                            return $hasil_rupiah;
                                        }
                                    @endphp

                                    @foreach ($produk as $produks)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="single-product shadow-sm rounded bg-white">
                                                <div class="product-img position-relative">
                                                    <span class="pro-label new-label bg-success text-white p-1 rounded">New</span>
                                                    <a href="{{ route('detail_produk', $produks->id_produk) }}">
                                                        <img src="/produk/{{ $produks->foto_produk1 }}" alt="{{ $produks->nama_produk }}" class="img-fluid rounded-top" />
                                                    </a>
                                                </div>
                                                <div class="product-info text-center p-3">
                                                    <h4 class="post-title mb-2">
                                                        <a href="{{ route('detail_produk', $produks->id_produk) }}" class="text-dark">{{ Str::upper($produks->nama_produk) }}</a>
                                                    </h4>
                                                    <div class="product-price mb-3">
                                                        <span class="text-danger font-weight-bold">
                                                            @php
                                                                if(Auth::check() && Auth::user()->is_b2b && $produks->tipe_produk == 'grosir') {
                                                                    echo rupiah($produks->harga_produk1) . ' <span class="badge badge-success text-xs" style="font-size:9px;">B2B</span>';
                                                                } else {
                                                                    echo rupiah($produks->harga_eceran);
                                                                }
                                                            @endphp
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('detail_produk', $produks->id_produk) }}" class="btn btn-primary w-100">Pesan Produk</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- Single-product end -->
                                </div>
                            </div>
                        </div>
                        <!-- Pagination start -->
                        <div class="shop-pagination text-center mt-4">
                            <div class="pagination">
                                <ul>
                                    {{ $produk->links('pagination::bootstrap-4') }}
                                </ul>
                            </div>
                        </div>
                        <!-- Pagination end -->
                    </div>
                    <!-- Shop-Content End -->
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Styling for the product area */
        .product-area {
            background-color: #f9f9f9;
            padding-top: 80px;
            padding-bottom: 80px;
        }

        .single-product {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .single-product:hover {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-img {
            position: relative;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pro-label {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .product-info {
            padding: 20px;
        }

        .product-info h4 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #d9534f;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .pagination {
            display: inline-block;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #f1f1f1;
            color: #333;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination a:hover, .pagination .active {
            background-color: #007bff;
            color: white;
        }

        .widget-categories .widget-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .widget-categories ul {
            list-style: none;
            padding-left: 0;
        }

        .widget-categories ul li a {
            color: #555;
            font-size: 16px;
            padding: 8px;
            display: block;
            text-decoration: none;
        }

        .widget-categories ul li a:hover {
            background-color: #007bff;
            color: white;
        }

    </style>
@endpush

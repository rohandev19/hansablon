@extends('home.layout.master')

@section('content')
    <!-- PRODUCT AREA START -->
    <div class="product-area pt-80 pb-80 product-style-2">
        <div class="container">
            <div class="row">
                <!-- Sidebar Kategori -->
                <div class="col-lg-3 order-2 order-lg-1 mb-4 mb-lg-0">
                    <aside class="widget widget-categories mb-30">
                        <div class="widget-title">
                            <h4 class="text-primary">Kategori Produk</h4>
                        </div>
                        <div id="cat-treeview" class="widget-info product-cat boxscrol2">
                            <ul>
                                <li class="open"><span>List Produk</span>
                                    <ul>
                                        @foreach ($kategori as $kategori)
                                            <li>
                                                <a href="{{ route('cari_kategori', $kategori->id_kategori) }}">
                                                    {{ $kategori->jenis_kategori }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>

                <!-- Produk -->
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="shop-content mt-tab-30 mb-30">
                        <div class="tab-content">
                            <div class="tab-pane active" id="grid-view">
                                <div class="row">

                                    @php
                                        function rupiah($angka) {
                                            return 'Rp ' . number_format($angka, 2, ',', '.');
                                        }
                                    @endphp

                                    @forelse ($produk as $produks)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="single-product shadow-sm rounded bg-white">
                                                <div class="product-img position-relative">
                                                    <span class="pro-label new-label bg-success text-white p-1 rounded">New</span>
                                                    <a href="{{ route('detail_produk', $produks->id_produk) }}">
                                                        <img src="/produk/{{ $produks->foto_produk1 }}" alt="{{ $produks->nama_produk }}" class="img-fluid rounded-top" />
                                                    </a>
                                                </div>
                                                <div class="product-info text-center p-3">
                                                    <h5 class="post-title mb-2">
                                                        <a href="{{ route('detail_produk', $produks->id_produk) }}" class="text-dark">
                                                            {{ Str::upper($produks->nama_produk) }}
                                                        </a>
                                                    </h5>
                                                    <div class="product-price mb-3 text-danger font-weight-bold">
                                                        @php
                                                            if(Auth::check() && Auth::user()->is_b2b && $produks->tipe_produk == 'grosir') {
                                                                echo rupiah($produks->harga_produk1) . ' <span class="badge badge-success text-xs" style="font-size:9px;">B2B</span>';
                                                            } else {
                                                                echo rupiah($produks->harga_eceran);
                                                            }
                                                        @endphp
                                                    </div>
                                                    <a href="{{ route('detail_produk', $produks->id_produk) }}" class="btn btn-warning w-100">Pesan Produk</a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-danger text-center" role="alert">
                                                Maaf, produk belum tersedia.
                                            </div>
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="shop-pagination text-center mt-4">
                            <div class="pagination">
                                {{ $produk->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

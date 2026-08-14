@extends('customer.layouts.master')

@section('css')
    {{-- ======================================================= --}}
    {{-- PERUBAHAN 1: TAMBAHKAN CSS KUSTOM DI SINI --}}
    <style>
        /* Memberikan style dasar pada setiap kartu produk */
        .product-card {
            border: 1px solid #e9e9e9; /* Garis tepi tipis berwarna abu-abu */
            border-radius: 8px;       /* Membuat sudut sedikit melengkung */
            overflow: hidden;         /* Memastikan gambar tidak keluar dari border radius */
            transition: all 0.3s ease;/* Efek transisi halus untuk semua perubahan */
            height: 100%;             /* Membuat semua kartu memiliki tinggi yang sama dalam satu baris */
            display: flex;
            flex-direction: column;
        }

        /* Efek saat mouse diarahkan ke kartu produk */
        .product-card:hover {
            transform: translateY(-5px); /* Sedikit mengangkat kartu ke atas */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); /* Memberikan efek bayangan yang lebih jelas */
            border-color: #556ee6; /* Mengubah warna border menjadi warna primer tema */
        }
        
        /* Memastikan link produk menutupi seluruh area kartu */
        .product-link {
            text-decoration: none; /* Menghapus garis bawah pada link */
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Mengatur agar gambar produk memenuhi ruang yang tersedia */
        .product-img {
            padding: 1rem;
            background-color: #fff;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-img img {
            max-height: 200px; /* Atur tinggi maksimum gambar jika perlu */
            object-fit: contain;
        }

        /* Mengatur area teks (nama & harga) */
        .product-details {
            padding: 1rem;
            border-top: 1px solid #e9e9e9; /* Garis pemisah antara gambar dan teks */
            background-color: #fcfcfc;
        }
    </style>
    {{-- ======================================================= --}}
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Produk Grosir</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Our Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-3 rounded mt-4">
                                <h5 class="font-size-16">Kategori Produk</h5>

                                <div id="accordion" class="custom-accordion categories-accordion">
                                    <div class="categories-group-card">
                                        <a href="#collapseTwo" class="categories-group-list" data-bs-toggle="collapse"
                                            aria-expanded="true" aria-controls="collapseTwo">
                                            <i class="ti-archive font-size-16 align-middle me-2"></i> Kategori
                                            <i class="mdi mdi-minus float-end accor-plus-icon"></i>
                                        </a>
                                        <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                                            <div>
                                                <ul class="list-unstyled categories-list mb-0">
                                                    <li class="{{ request()->routeIs('customer.produk') ? 'active' : '' }}"><a href="{{ route('customer.produk') }}"><i
                                                                class="mdi mdi-circle-medium me-1"></i>
                                                            LIHAT SEMUA
                                                        </a>
                                                    </li>
                                                    @forelse ($kategori as $item)
                                                        {{-- Ganti variabel $kategori menjadi $item agar tidak bentrok --}}
                                                        <li class="{{ request()->is('customer/kategori-produk/'.$item->id_kategori) ? 'active' : '' }}"><a
                                                                href="{{ route('customer.kategori_produk', $item->id_kategori) }}"><i
                                                                    class="mdi mdi-circle-medium me-1"></i>
                                                                {{ Str::upper($item->jenis_kategori) }}
                                                            </a>
                                                        </li>
                                                    @empty
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        @php
                            function rupiah($angka)
                            {
                                // Menghapus ,00 di belakang
                                $hasil_rupiah = 'Rp ' . number_format($angka, 0, ',', '.');
                                return $hasil_rupiah;
                            }
                        @endphp
                        @forelse ($produk as $produks)
                            <div class="col-xl-4 col-sm-6 mb-4">
                                {{-- ======================================================= --}}
                                {{-- PERUBAHAN 2: TAMBAHKAN KELAS "product-card" --}}
                                <div class="card product-card">
                                    <a href="{{ route('customer.detail_produk', $produks->id_produk) }}" class="product-link">
                                        <div class="product-img">
                                            <img src="/produk/{{ $produks->foto_produk1 }}"
                                                alt="{{ Str::title($produks->nama_produk) }}"
                                                class="img-fluid mx-auto d-block">
                                        </div>

                                        <div class="product-details text-center">
                                            <h5 class="font-size-16 text-dark mb-2 text-truncate">{{ Str::title($produks->nama_produk) }}</h5>

                                            <h5 class="mt-2 mb-0 text-dark fw-bold">
                                                @php
                                                    echo rupiah($produks->harga_produk1);
                                                @endphp
                                            </h5>
                                        </div>
                                    </a>
                                </div>
                                {{-- ======================================================= --}}
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="alert alert-warning mb-0 text-center" role="alert">
                                            <h4 class="alert-heading">Maaf!</h4>
                                            <p>Produk dalam kategori ini tidak ditemukan.</p>
                                            <hr>
                                            <p class="mb-0">Silakan coba lihat kategori lain atau hubungi admin kami.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                           {{-- Menampilkan pagination di tengah --}}
                           <div class="d-flex justify-content-center mt-4">
                                {{ $produk->links('pagination::bootstrap-5') }}
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

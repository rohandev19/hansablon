@extends('customer.layouts.master')

@section('content')
    @php
        function rupiah($angka) {
            return 'Rp ' . number_format($angka, 2, ',', '.');
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

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- Image Section --}}
                                <div class="col-xl-5">
                                    <div class="product-detail row">
                                        <div class="col-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                                                @for ($i = 1; $i <= 4; $i++)
                                                    <a class="nav-link {{ $i == 1 ? 'active' : '' }}" id="product-{{ $i }}-tab" data-bs-toggle="pill"
                                                        href="#product-{{ $i }}" role="tab">
                                                        <img src="/produk/{{ $produk['foto_produk' . $i] }}" class="img-fluid mx-auto d-block tab-img rounded" alt="">
                                                    </a>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                @for ($i = 1; $i <= 4; $i++)
                                                    <div class="tab-pane fade {{ $i == 1 ? 'show active' : '' }}" id="product-{{ $i }}" role="tabpanel">
                                                        <div class="product-img">
                                                            <img src="/produk/{{ $produk['foto_produk' . $i] }}" class="img-fluid mx-auto d-block" alt="">
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Info --}}
                                <div class="col-xl-7">
                                    <div class="mt-4 mt-xl-3">
                                        <a href="#" class="text-primary">{{ Str::upper($produk->jenis_kategori) }}</a>
                                        <h5 class="mt-1 mb-3">{{ Str::title($produk->nama_produk) }}</h5>
                                        <h5 class="mt-2">{{ rupiah($produk->harga_eceran) }}</h5>

                                        <hr class="my-4">

                                        <div class="mt-4">
                                            <h6>List Harga</h6>
                                            <table class="table table-bordered mt-2">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $produk->harga_eceran ? rupiah($produk->harga_eceran) : 'Harga Tidak Tersedia' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-4">
                                            <h6>Warna & Ukuran :</h6>
                                            <p class="text-muted mb-2"><i class="mdi mdi-check-bold text-success me-2"></i>Tersedia Seluruh Warna</p>
                                            <p class="text-muted mb-2"><i class="mdi mdi-check-bold text-success me-2"></i>Tersedia Seluruh Ukuran Pakaian</p>
                                        </div>

                                        <form action="{{ route('keranjang.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                            <input type="number" name="demo0" value="1" min="1" required>
                                            <button type="submit" class="btn btn-primary mt-3">
                                                <i class="mdi mdi-cart me-2"></i> Masukan Keranjang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Deskripsi</h4>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                            Deskripsi {{ Str::title($produk->nama_produk) }}
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            {!! htmlspecialchars_decode($produk->deskripsi) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Komentar --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Reviews:</h4>
                            <div class="border p-4 rounded">
                                @forelse ($komentar as $k)
                                    <div class="media border-bottom pb-3">
                                        <div class="media-body">
                                            <p class="text-muted mb-2">{{ $k->komentar_produk }}</p>
                                            <h5 class="font-size-15 mb-3">{{ Str::title($k->nama) }}</h5>
                                            <p class="float-sm-right font-size-12">{{ $k->updated_at }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Tidak Ada Komentar</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/morvin/dist/assets/libs/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
@endsection

@section('js')
    <script src="/morvin/dist/assets/libs/select2/js/select2.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
@endsection

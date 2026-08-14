@extends('home.layout.master')
@section('content')
<section class="slider-banner-area clearfix">
    <!-- Sidebar-social-media start -->
    <div class="sidebar-social d-none d-md-block">
        <div class="table">
            <div class="table-cell">
         
            </div>
        </div>
    </div>
    <!-- Sidebar-social-media start -->
    <div class="banner-left floatleft">
        <!-- Slider-banner start -->
        <div class="slider-banner">
            <div class="single-banner banner-1">
                <a class="banner-thumb" href="#"><img src="/annprint/banner-4.jpg"
                        alt="" /></a>
            </div>
            <div class="single-banner banner-2">
                <a class="banner-thumb" href="#"><img src="/annprint/banner-3.jpg"
                        alt="" /></a>
            </div>
        </div>
        <!-- Slider-banner end -->
    </div>
    <div class="slider-right floatleft">
        <!-- Slider-area start -->
        <div class="slider-area">
            <div class="bend niceties preview-2">
                <div id="ensign-nivoslider" class="slides">
                    <img src="/annprint/slider-1.jpg" alt="" title="#slider-direction-1" />
                    <img src="/annprint/slider-2.jpg" alt="" title="#slider-direction-2" />
                    <img src="/annprint/slider-3.jpg" alt="" title="#slider-direction-3" />
                </div>
                <!-- direction 1 -->
                <div id="slider-direction-1" class="t-cn slider-direction">
                    <div class="slider-progress"></div>
                    <div class="slider-content t-lfl s-tb slider-1">
                        <div class="title-container s-tb-c title-compress">
                            <div class="layer-1">
                                <div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                                    <h2 class="slider-title3 text-uppercase mb-0" style="color:white">Selamat
                                        Datang Di </h2>
                                </div>
                                <div class="wow fadeIn" data-wow-duration="1.5s" data-wow-delay="1.5s">
                                    <h2 class="slider-title1 text-uppercase mb-0" style="color:white">Galery
                                    </h2>
                                </div>
                                <div class="wow fadeIn" data-wow-duration="2s" data-wow-delay="2.5s">
                                    <h3 class="slider-title2 text-uppercase" style="color:white">HANN PRINT
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- direction 2 -->
                <div id="slider-direction-2" class="slider-direction">
                    <div class="slider-progress"></div>
                    <div class="slider-content t-lfl s-tb slider-1">
                        <div class="title-container s-tb-c title-compress">
                            <div class="layer-1">
                                <div class="wow fadeInUpBig" data-wow-duration="1.5s" data-wow-delay="0.5s">
                                    <h2 class="slider-title1 text-uppercase">HANN PRINT</h2>
                                </div>
                                <div class="wow fadeInUpBig" data-wow-duration="2s" data-wow-delay="0.5s">
                                    <p class="slider-pro-brief">
                                        UMKM yang berjalan di bidang percetakan dan sablon pakaian baik secara
                                        tradisional ataupun digital yang ada di Depok,Jawa Barat
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- direction 3 -->
                <div id="slider-direction-3" class="slider-direction">
                    <div class="slider-progress"></div>
                    <div class="slider-content t-lfl s-tb slider-1">
                        <div class="title-container s-tb-c title-compress">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider-area end -->
    </div>
    <!-- Sidebar-social-media start -->
    <div class="sidebar-account d-none d-md-block">
        <div class="table">
            <div class="table-cell">
                <ul>
                  
                    </li>
                    <!-- <li><a href="wishlist.html" title="Wishlist"><i class="fas fa-heart"></i></a></li> -->
                </ul>
            </div>
        </div>
    </div>
    <!-- Sidebar-social-media start -->
</section>
<!-- End Slider-section -->
<!-- sidebar-search Start -->
<div class="sidebar-search animated slideOutUp">
    <div class="table">
        <div class="table-cell">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 p-0">
                        <div class="search-form-wrap">
                            <button class="close-search"><i class="fas fa-times"></i></button>
                            <form action="#">
                                <input type="text" placeholder="Search here..." />
                                <button class="search-button" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- sidebar-search End -->
<!-- PRODUCT-AREA START -->
<div class="product-area pt-80 pb-35">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <h2 class="title-border">Sekilas Product</h2>
                </div>
                <div class="product-slider style-1 arrow-left-right">
                    <!-- Single-product start -->
                    @php
                        function rupiah($angka)
                        {
                            $hasil_rupiah = 'Rp ' . number_format($angka, 2, ',', '.');
                            return $hasil_rupiah;
                        }
                    @endphp
                    @foreach ($produk as $produk)
                        <div class="single-product">
                            <div class="product-img">
                                <a href="{{ route('detail_produk', $produk->id_produk) }}"><img src="/produk/{{ $produk->foto_produk1 }}"
                                        alt="" /></a>
                            </div>
                            <div class="product-info clearfix">
                                <div class="fix">
                                    <h4 class="post-title floatleft"><a
                                            href="{{ route('detail_produk', $produk->id_produk) }}">{{ Str::upper($produk->nama_produk) }}</a></h4>
                                    <p class="floatright hidden-sm d-none d-md-block">{{ Str::upper($produk->jenis_produk) }}</p>
                                </div>
                                <div class="fix">
                                    <span class="pro-price floatleft">
                                        @php
                                            if(Auth::check() && Auth::user()->is_b2b && $produk->tipe_produk == 'grosir') {
                                                echo rupiah($produk->harga_produk1) . ' <span class="badge badge-success text-xs" style="font-size:9px;">B2B</span>';
                                            } else {
                                                echo rupiah($produk->harga_eceran);
                                            }
                                        @endphp
                                    </span>
                                    <span class="pro-rating floatright">
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PRODUCT-AREA END -->
<!-- DISCOUNT-PRODUCT START -->
<div class="discount-product-area">
    <div class="container">
        <div class="row">
            <div class="discount-product-slider dots-bottom-right">
                <!-- single-discount-product start -->
                <div class="col-lg-12">
                    <div class="discount-product">
                        <img src="/annprint/diskon.jpg" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DISCOUNT-PRODUCT END -->
@endsection

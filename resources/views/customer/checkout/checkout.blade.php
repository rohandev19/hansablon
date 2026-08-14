@extends('customer.layouts.master')

@section('css')
    {{-- CSS untuk Select2 dan form lainnya --}}
    <link href="/morvin/dist/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/morvin/dist/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />

    {{-- CSS KUSTOM UNTUK KARTU ALAMAT --}}
    <style>
        .address-card {
            border: 2px solid #e9e9ef;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            position: relative;
        }

        .address-card:hover {
            border-color: #c5c5e2;
        }

        .address-card.selected {
            border-color: #556ee6; /* Warna primer tema Anda */
            box-shadow: 0 0 0 2px rgba(85, 110, 230, 0.25);
        }

        .address-card .radio-hidden {
            display: none; /* Sembunyikan radio button asli */
        }

        .address-card .checkmark-icon {
            display: none; /* Sembunyikan ikon centang secara default */
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 28px;
            height: 28px;
            background-color: #556ee6; /* Warna primer tema Anda */
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-size: 16px;
        }

        .address-card.selected .checkmark-icon {
            display: block; /* Tampilkan ikon centang saat kartu dipilih */
        }

        .address-card h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .address-card p {
            font-size: 0.9rem;
            color: #545a61;
            margin-bottom: 0.25rem;
        }
        
        .address-actions {
            margin-top: 1rem;
        }

        .address-actions a {
            font-size: 0.85rem;
            font-weight: 500;
            margin-right: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Checkout</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Checkout</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            @error('alamat_kirim')
                                <div class="alert alert-id alert-danger" role="alert">
                                    Gagal! Diwajibkan Untuk Memilih Alamat Pengiriman.
                                </div>
                            @enderror
                            @if (session('error'))
                                <div class="alert alert-id alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @elseif (session('success'))
                                <div class="alert alert-id alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <h5 class="header-title">Checkout Barang</h5>
                            <p class="card-title-desc">Informasi Data Pemesan</p>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="billing-name">Nama Lengkap</label>
                                        <input type="text" value="{{ Str::upper(Auth::user()->nama) }}" class="form-control" id="billing-name" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="billing-email-address">Alamat Email</label>
                                        <input type="email" value="{{ Auth::user()->email }}" class="form-control" id="billing-email-address" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="header-title mb-0">Alamat Penerima Produk</h5>
                                <a href="{{ route('customer.alamat_checkout', $id) }}" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-plus me-2"></i>Tambah Alamat</a>
                            </div>

                            <form action="{{ route('pesanan.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <input type="text" name="id_keranjang" value="{{ $id }}" hidden>

                                {{-- ====================================================== --}}
                                {{-- MULAI PERUBAHAN TAMPILAN ALAMAT --}}
                                {{-- ====================================================== --}}
                                <div class="row">
                                    @forelse ($alamat as $item)
                                        <div class="col-12">
                                            {{-- Kartu Alamat yang bisa diklik --}}
                                            <div class="address-card">
                                                {{-- Radio button asli, sekarang disembunyikan tapi tetap berfungsi untuk form --}}
                                                <input class="radio-hidden" type="radio" name="alamat_kirim" 
                                                       value="{{ $item->id_kota . '|' . $item->id_user_alamat }}" 
                                                       id="alamat-{{ $item->id_user_alamat }}"
                                                       {{ old('alamat_kirim') == ($item->id_kota . '|' . $item->id_user_alamat) ? 'checked' : '' }}>
                                                
                                                {{-- Ikon centang akan muncul di sini jika terpilih --}}
                                                <div class="checkmark-icon">
                                                    <i class="mdi mdi-check"></i>
                                                </div>

                                                {{-- Detail Alamat --}}
                                                <h5>{{ Str::title($item->nama_penerima) }}</h5>
                                                <p class="mb-1">{{ $item->alamat . ', ' . $item->nama_kota . ' [' . $item->nama_prov . ']' }}</p>
                                                <p class="mb-0">Tlp. {{ $item->no_telp }}</p>

                                                {{-- Tombol Aksi (Opsional) --}}
                                                <div class="address-actions">
                                                    {{-- <a href="#" class="text-primary">Ubah</a> --}}
                                                    {{-- <a href="#" class="text-danger">Hapus</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="card alert border mt-lg-0 p-0 mb-0">
                                                <div class="card-header bg-soft-danger">
                                                    <h5 class="font-size-16 text-danger my-1">Alamat Tidak Ditemukan</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="mb-2">
                                                            <i class="mdi mdi-alert-outline display-4 text-danger"></i>
                                                        </div>
                                                        <p>Anda dapat menambahkan alamat penerima barang pada tombol di atas.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                {{-- ====================================================== --}}
                                {{-- AKHIR PERUBAHAN TAMPILAN ALAMAT --}}
                                {{-- ====================================================== --}}

                                <div class="row mt-4">
                                    {{-- Cek jika ada alamat, baru tampilkan sisa form --}}
                                    @if ($alamat->count() > 0)
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Pilih Variasi Produk / <span class="text-danger">* Kosongkan Jika Tidak Ada</span></label>
                                                <select class="form-control select2" id="variasiId">
                                                    <option value="">Pilih Variasi Produk [Bisa Pilih Lebih Dari Satu]</option>
                                                    @foreach ($variasi as $v)
                                                        <option data-doj="{{ $v->harga_variasi }}" data-city="{{ $v->jenis_variasi }}">
                                                            {{ Str::upper($v->jenis_variasi) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <input class="form-control" type="text" name="variasi" id="variasi_result" readonly placeholder="Variasi yang dipilih akan muncul di sini">
                                                <input type="text" value="" name="variasi_harga" id="variasi_harga" hidden>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label">Pilih Aplikasi Sablon / <span class="text-danger">* Kosongkan Jika Tidak Ada</span></label>
                                                <select class="form-control select2" id="sablonId">
                                                    <option value="">Pilih Aplikasi Sablon [Bisa Pilih Lebih Dari Satu]</option>
                                                    @foreach ($sablon as $s)
                                                        <option data-doj="{{ $s->harga_sablon }}" data-city="{{ $s->jenis_sablon }}">
                                                            {{ Str::upper($s->jenis_sablon) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <input class="form-control" type="text" name="sablon" id="sablon_result" readonly placeholder="Aplikasi sablon yang dipilih akan muncul di sini">
                                                <input type="text" value="" name="sablon_harga" id="sablon_harga" hidden>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan Untuk Pesanan <span class="text-danger">* Kosongkan Jika Tidak Ada Catatan</span></label>
                                                <div>
                                                    <textarea class="form-control" name="note" rows="5" placeholder="Catatan Variasi dan Sablon Produk"></textarea>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-100"><i class="mdi mdi-newspaper-plus me-2"></i> Checkout Pesanan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0" style="width: 100px;" scope="col">Photo</th>
                                            <th class="border-top-0" scope="col">Product</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($keranjang as $item_keranjang)
                                            <tr>
                                                <td><img src="/produk/{{ $item_keranjang->produk->foto_produk1 ?? '' }}" alt="product-img" title="product-img" class="avatar-md"></td>
                                                <td>
                                                    <h5 class="font-size-16 text-truncate"><a href="#" class="text-reset">{{ Str::title($item_keranjang->produk->nama_produk ?? 'Unknown') }}</a></h5>
                                                    <p class="font-size-14 mb-0 text-muted">Quantity : {{ $item_keranjang->total }} Pcs</p>
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
    </div>
@endsection

@section('js')
    {{-- JS untuk Select2 dan form lainnya --}}
    <script src="/morvin/dist/assets/libs/select2/js/select2.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="/morvin/dist/assets/js/pages/form-advanced.init.js"></script>

    <script>
        // Auto-hide alert
        window.setTimeout(function() {
            $(".alert-id").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);

        $(document).ready(function() {

            // ======================================================
            // JAVASCRIPT BARU UNTUK SELEKSI KARTU ALAMAT
            // ======================================================
            // Cek saat halaman dimuat, jika ada radio button yang sudah terpilih (misal karena error validasi)
            $('.radio-hidden').each(function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.address-card').addClass('selected');
                }
            });

            // Tambahkan event click ke setiap kartu alamat
            $('.address-card').on('click', function() {
                // 1. Hapus kelas 'selected' dari SEMUA kartu alamat
                $('.address-card').removeClass('selected');
                
                // 2. Tambahkan kelas 'selected' ke kartu yang DIKLIK
                $(this).addClass('selected');
                
                // 3. Cari radio button yang tersembunyi di dalam kartu yang diklik, dan set statusnya menjadi 'checked'
                $(this).find('.radio-hidden').prop('checked', true);
            });


            // ======================================================
            // Kode JavaScript Anda yang sudah ada
            // ======================================================
            $("#variasiId").change(function() {
                var cntrol = $(this);
                var Item = cntrol.find(':selected').data('city');
                var variasi = $('#variasi_result');
                var value = variasi.val();
                var finalvalue = value + Item + ', ';
                if (cntrol.val() == "") finalvalue = "";
                $('#variasi_result').val(finalvalue);
            });

            $("#variasiId").change(function() {
                var cntrol = $(this);
                var item_harga = cntrol.find(':selected').data('doj');
                var variasi_harga = $('#variasi_harga').val();
                var finalharga = variasi_harga + item_harga + ', ';
                if (cntrol.val() == "") finalharga = "";
                $('#variasi_harga').val(finalharga);
            });

            $("#sablonId").change(function() {
                var cntrol = $(this);
                var Item = cntrol.find(':selected').data('city');
                var variasi = $('#sablon_result');
                var value = variasi.val();
                var finalvalue = value + Item + ', ';
                if (cntrol.val() == "") finalvalue = "";
                $('#sablon_result').val(finalvalue);
            });

            $("#sablonId").change(function() {
                var cntrol = $(this);
                var item_harga = cntrol.find(':selected').data('doj');
                var variasi_harga = $('#sablon_harga').val();
                var finalharga = variasi_harga + item_harga + ', ';
                if (cntrol.val() == "") finalharga = "";
                $('#sablon_harga').val(finalharga);
            });
        });
    </script>
@endsection

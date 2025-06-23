@extends('customer.layouts.master')

@section('css')
    <style>
        .step-card {
            margin-bottom: 1.5rem;
        }

        .step-title {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #556ee6;
            color: #fff;
            font-weight: 600;
            margin-right: 12px;
        }

        .rincian-table td {
            border-top: 1px solid #f0f0f0;
            padding: 0.75rem;
        }

        .rincian-table tr:last-child td {
            border-bottom: none;
        }

        .rincian-table .item-name {
            font-weight: 500;
        }

        .rincian-table .item-price {
            text-align: right;
        }

        .rincian-total {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .rekening-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: box-shadow 0.2s ease;
        }

        .rekening-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .rekening-card img {
            max-height: 40px;
            margin-right: 1rem;
        }

        .rekening-details {
            flex-grow: 1;
        }

        .rekening-details h6 {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .rekening-details p {
            margin-bottom: 0;
            color: #555;
        }

        .btn-copy {
            white-space: nowrap;
        }

        .payment-sidebar {
            position: sticky;
            top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Pembayaran Pesanan</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pembayaran Pesanan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @php
            function rupiah($angka)
            {
                return 'Rp ' . number_format($angka, 0, ',', '.');
            }
        @endphp

        <div class="page-content-wrapper">
            <form action="{{ route('pesanan.update', $pesanan->id_pesanan) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card step-card">
                            <div class="card-body">
                                <div class="step-title">
                                    <span class="step-number">1</span>
                                    <h5 class="mb-0">Ringkasan Pesanan & Pengiriman</h5>
                                </div>
                                <table class="table table-borderless rincian-table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- IMG SRC SUDAH DIPERBAIKI DENGAN ASSET() --}}
                                                    <img src="{{ asset('produk/' . $pesanan->foto_produk1) }}"
                                                        alt="Foto Produk"
                                                        onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}';"
                                                        class="avatar-sm me-3 rounded">
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::upper($pesanan->nama_produk) }}</h6>
                                                        <small class="text-muted">Qty: {{ $pesanan->quantity }} pcs</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="item-price">{{ rupiah($pesanan->bayar) }}</td>
                                        </tr>
                                        @if (!empty($pesanan->variasi))
                                            <tr>
                                                <td class="item-name ps-5">Variasi: {{ Str::title($pesanan->variasi) }}</td>
                                                <td class="item-price">{{ rupiah($pesanan->variasi_total) }}</td>
                                            </tr>
                                        @endif
                                        @if (!empty($pesanan->sablon))
                                            <tr>
                                                <td class="item-name ps-5">Sablon: {{ Str::title($pesanan->sablon) }}</td>
                                                <td class="item-price">{{ rupiah($pesanan->sablon_total) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="item-name">Biaya Pengiriman (JNE REG)</td>
                                            <td class="item-price">{{ rupiah($pesanan->ongkir) }}</td>
                                        </tr>
                                        <tr class="rincian-total">
                                            <td class="item-name text-end">Total Tagihan</td>
                                            <td class="item-price">{{ rupiah($pesanan->total_bayar) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <h6><b>Alamat Pengiriman</b></h6>
                                <p class="mb-1">{{ Str::title($pesanan->nama_penerima) }} ({{ $pesanan->no_telp }})</p>
                                <p class="text-muted mb-0">
                                    {{ Str::title($pesanan->alamat . ', ' . $pesanan->nama_kota . ' [ ' . $pesanan->nama_prov . ']') }}
                                </p>
                            </div>
                        </div>

                        <div class="card step-card">
                            <div class="card-body">
                                <div class="step-title">
                                    <span class="step-number">2</span>
                                    <h5 class="mb-0">Pilih Tujuan Transfer</h5>
                                </div>
                                @foreach ($rekening as $rek)
                                    <div class="rekening-card">
                                        <img src="{{ asset('images/bank/' . $rek->logo) }}" alt="Logo {{ $rek->nama_rek }}">
                                        <div class="rekening-details">
                                            <h6>{{ Str::upper($rek->nama_rek) }}</h6>
                                            <p>No. Rekening: <strong>{{ $rek->no_rek }}</strong></p>
                                            <p>Atas Nama {{ Str::title($rek->atas_nama) }}</p>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm btn-copy"
                                            data-copy="{{ $rek->no_rek }}">
                                            <i class="mdi mdi-content-copy me-1"></i> Salin No. Rek
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card step-card">
                            <div class="card-body">
                                <div class="step-title">
                                    <span class="step-number">3</span>
                                    <h5 class="mb-0">Detail Desain (Opsional)</h5>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload File Desain</label>
                                    <input type="file" name="desain" class="form-control">
                                    @if (!empty($pesanan->desain))
                                        <small class="text-muted mt-1 d-block">Desain sebelumnya: <span
                                                class="text-primary">{{ $pesanan->desain }}</span></small>
                                    @endif
                                </div>
                                <div>
                                    <label class="form-label">Request / Catatan Desain</label>
                                    <textarea name="request_desain" class="form-control" rows="4"
                                        placeholder="Contoh: Logo di depan, tulisan di belakang.">{{ $pesanan->request_user }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card payment-sidebar">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Konfirmasi Pembayaran</h5>
                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran<span class="text-danger">*</span></label>
                                    <select class="form-select @error('metode') is-invalid @enderror" name="metode"
                                        required>
                                        <option value="" selected disabled>Pilih Jumlah Bayar</option>
                                        <option value="lunas">Lunas ({{ rupiah($pesanan->total_bayar) }})</option>
                                        <option value="dp">DP 50% ({{ rupiah($pesanan->total_bayar * 0.5) }})</option>
                                    </select>
                                    @error('metode')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Upload Bukti Pembayaran<span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="bukti_bayar"
                                        class="form-control @error('bukti_bayar') is-invalid @enderror" accept="image/*"
                                        id="imgInp1" required>
                                    @error('bukti_bayar')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <img id="output1" src="{{ asset('images/upload.png') }}" class="img-fluid rounded"
                                        style="width: 100%; max-height: 200px; object-fit: contain; border: 1px solid #ddd; padding: 5px;" />
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="mdi mdi-send me-2"></i> Kirim Bukti Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        imgInp1.onchange = evt => {
            const [file] = imgInp1.files
            if (file) {
                output1.src = URL.createObjectURL(file)
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const copyButtons = document.querySelectorAll('.btn-copy');
            copyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const numberToCopy = this.getAttribute('data-copy');
                    navigator.clipboard.writeText(numberToCopy).then(() => {
                        const originalText = this.innerHTML;
                        this.innerHTML = 'Berhasil Disalin!';
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-success');
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-primary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Gagal menyalin: ', err);
                    });
                });
            });
        });
    </script>
@endsection
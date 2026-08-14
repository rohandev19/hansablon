<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Roles (Admin, Pembeli B2C, Pembeli B2B)
        $admin = User::create([
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'tipe' => 'admin',
        ]);

        $b2c = User::create([
            'nama' => 'Pelanggan Biasa (B2C)',
            'email' => 'b2c@pembeli.com',
            'password' => Hash::make('pembeli123'),
            'tipe' => 'pembeli',
            'is_b2b' => false,
        ]);

        $b2b = User::create([
            'nama' => 'Mitra Reseller (B2B)',
            'email' => 'b2b@pembeli.com',
            'password' => Hash::make('reseller123'),
            'tipe' => 'pembeli',
            'is_b2b' => true,
        ]);

        // 1.5 Dummy Kategori
        $kategoriKaos = \App\Models\Kategori::create([
            'jenis_kategori' => 'Kaos',
        ]);
        
        $kategoriMug = \App\Models\Kategori::create([
            'jenis_kategori' => 'Mug',
        ]);

        // 2. Dummy Produk (Grosir & Eceran)
        $produkGrosir = \App\Models\Produk::create([
            'nama_produk' => 'Kaos Polos Cotton Combed 30s (Grosir)',
            'deskripsi' => 'Kaos polos berkualitas cocok untuk sablon.',
            'kategori' => $kategoriKaos->id_kategori,
            'tipe_produk' => 'grosir',
            'stok' => 500,
            'harga_eceran' => 45000,
            'harga_produk1' => 35000,
            'harga_produk2' => 33000,
            'harga_produk3' => 31000,
            'harga_produk4' => 29000,
            'harga_produk5' => 28000,
            'foto_produk1' => 'dummy_kaos.jpg',
        ]);

        $produkEceran = \App\Models\Produk::create([
            'nama_produk' => 'Mug Sablon Custom (Eceran)',
            'deskripsi' => 'Mug dengan sablon custom.',
            'kategori' => $kategoriMug->id_kategori,
            'tipe_produk' => 'eceran',
            'stok' => 100,
            'harga_eceran' => 25000,
            'harga_produk1' => null,
            'foto_produk1' => 'dummy_mug.jpg',
        ]);

        // 3. Dummy Alamat untuk B2C dan B2B
        $alamatB2C = \App\Models\Alamat::create([
            'id_user' => $b2c->id,
            'nama_penerima' => 'John Doe',
            'no_telp' => '081234567890',
            'id_provinsi' => 9,
            'nama_prov' => 'Jawa Barat',
            'id_kota' => 115,
            'nama_kota' => 'Depok',
            'alamat' => 'Jl. Margonda Raya No. 100',
        ]);

        $alamatB2B = \App\Models\Alamat::create([
            'id_user' => $b2b->id,
            'nama_penerima' => 'Toko Reseller Maju',
            'no_telp' => '089876543210',
            'id_provinsi' => 6,
            'nama_prov' => 'DKI Jakarta',
            'id_kota' => 152,
            'nama_kota' => 'Jakarta Pusat',
            'alamat' => 'Tanah Abang Blok A',
        ]);

        // 4. Dummy Pesanan
        $statuses = ['menunggu pembayaran', 'Bukti Pembayaraan Sedang Di Tinjau', 'Pesanan Sedang Di Proses', 'Barang Dalam Pengiriman', 'Pesanan Di Terima', 'Pesanan Di Tolak'];
        
        for ($i = 0; $i < 15; $i++) {
            $is_b2b_order = rand(0, 1) == 1;
            $user = $is_b2b_order ? $b2b : $b2c;
            $alamat = $is_b2b_order ? $alamatB2B : $alamatB2C;
            $produk = $is_b2b_order ? $produkGrosir : $produkEceran;
            
            $qty = $is_b2b_order ? rand(10, 50) : rand(1, 5);
            $harga = $is_b2b_order ? $produk->harga_produk1 : $produk->harga_eceran;
            $bayar = $qty * $harga;
            $status = $statuses[array_rand($statuses)];

            \App\Models\Pesanan::create([
                'id_user' => $user->id,
                'id_produk' => $produk->id_produk,
                'quantity' => $qty,
                'id_alamat' => $alamat->id_user_alamat,
                'id_kota' => $alamat->id_kota,
                'bayar' => $bayar,
                'ongkir' => 15000,
                'total_bayar' => $bayar + 15000,
                'status' => $status,
                'tipe_pembayaran' => 'lunas',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 10)),
            ]);
        }
        
        // 5. Dummy Rekening
        \App\Models\Rekening::create([
            'nama_rek' => 'BCA',
            'no_rek' => '1234567890',
        ]);
        
    }
}

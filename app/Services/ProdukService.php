<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProdukService
{
    /**
     * Create a new product with the given data.
     */
    public function createProduk(array $data): Produk
    {
        $this->processPrices($data);
        $this->processImages($data, $data['nama_produk'] ?? 'produk');

        return Produk::create([
            'nama_produk' => $data['nama_produk'],
            'tipe_produk' => $data['tipe_produk'] ?? 'grosir',
            'kategori' => $data['pilih_kategori'],
            'deskripsi' => $data['deskripsi_produk'],
            'stok' => $data['stok'],
            'harga_eceran' => $data['harga_eceran'] ?? null,
            'harga_produk1' => $data['harga_produk1'] ?? null,
            'harga_produk2' => $data['harga_produk2'] ?? null,
            'harga_produk3' => $data['harga_produk3'] ?? null,
            'harga_produk4' => $data['harga_produk4'] ?? null,
            'harga_produk5' => $data['harga_produk5'] ?? null,
            'foto_produk1' => $data['foto_produk1'] ?? null,
            'foto_produk2' => $data['foto_produk2'] ?? null,
            'foto_produk3' => $data['foto_produk3'] ?? null,
            'foto_produk4' => $data['foto_produk4'] ?? null,
        ]);
    }

    /**
     * Update an existing product.
     */
    public function updateProduk(Produk $produk, array $data): bool
    {
        $this->processPrices($data, true, $produk);
        $this->processImages($data, $data['nama_produk'] ?? 'produk');

        // Remap keys to match database columns
        $updateData = [
            'nama_produk' => $data['nama_produk'],
            'tipe_produk' => $data['tipe_produk'] ?? $produk->tipe_produk,
            'kategori' => $data['pilih_kategori'],
            'deskripsi' => $data['deskripsi_produk'],
            'stok' => $data['stok'],
            'harga_eceran' => $data['harga_eceran'] ?? $produk->harga_eceran,
            'harga_produk1' => $data['harga_produk1'] ?? $produk->harga_produk1,
            'harga_produk2' => $data['harga_produk2'] ?? $produk->harga_produk2,
            'harga_produk3' => $data['harga_produk3'] ?? $produk->harga_produk3,
            'harga_produk4' => $data['harga_produk4'] ?? $produk->harga_produk4,
            'harga_produk5' => $data['harga_produk5'] ?? $produk->harga_produk5,
        ];

        // Only update image fields if new images were uploaded
        for ($i = 1; $i <= 4; $i++) {
            $key = "foto_produk{$i}";
            if (isset($data[$key])) {
                $updateData[$key] = $data[$key];
            }
        }

        return $produk->update($updateData);
    }

    /**
     * Parse currency string to integer.
     */
    private function parseCurrency(?string $amount): ?int
    {
        if (!$amount) {
            return null;
        }
        
        $cleaned = preg_replace('/[Rp.,]/', '', $amount);
        $cleaned = substr($cleaned, 0, -2);
        
        return is_numeric($cleaned) ? (int) $cleaned : null;
    }

    /**
     * Process pricing fields from request data.
     */
    private function processPrices(array &$data, bool $isUpdate = false, ?Produk $produk = null): void
    {
        if (isset($data['harga_eceran'])) {
            $data['harga_eceran'] = $this->parseCurrency($data['harga_eceran']);
        }
        for ($i = 1; $i <= 5; $i++) {
            $key = "harga_produk{$i}";
            if (isset($data[$key])) {
                $data[$key] = $this->parseCurrency($data[$key]);
            } else if ($isUpdate && $produk) {
                 // For update, if not provided, keep existing (handled in update mapping)
            }
        }
    }

    /**
     * Process and upload image files.
     */
    private function processImages(array &$data, string $productName): void
    {
        for ($i = 1; $i <= 4; $i++) {
            $fileKey = "img{$i}";
            if (isset($data[$fileKey]) && $data[$fileKey] instanceof UploadedFile) {
                $file = $data[$fileKey];
                $filename = Str::slug($productName) . '-' . $i . '-' . time() . '.' . $file->getClientOriginalExtension();
                
                // Security-First: Sanitize and store safely
                $file->move(public_path('produk'), $filename);
                $data["foto_produk{$i}"] = $filename;
            }
        }
    }
}

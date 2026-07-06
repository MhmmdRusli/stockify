<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepo;

    // Inject ProductRepository ke Service
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    // Fungsi yang dicari oleh Controller Anda:
    public function getAllProducts()
    {
        return $this->productRepo->getAll();
    }

    // Fungsi untuk menyimpan produk dengan logika SKU otomatis
    public function storeProduct(array $data)
    {
        // Jika SKU kosong, sistem otomatis membuatkan kode SKU unik
        if (empty($data['sku'])) {
            $data['sku'] = 'PROD-' . strtoupper(Str::random(6));
        }

        return $this->productRepo->create($data);
    }

    // Fungsi untuk menghapus produk
    public function deleteProduct($id)
    {
        return $this->productRepo->delete($id);
    }

    // Fungsi untuk mengupdate produk beserta logika SKU jika dikosongkan
public function updateProduct($id, array $data)
{
    if (empty($data['sku'])) {
        $data['sku'] = 'PROD-' . strtoupper(Str::random(6));
    }

    return $this->productRepo->update($id, $data);
}
}
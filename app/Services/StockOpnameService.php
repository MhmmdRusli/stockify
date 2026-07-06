<?php

namespace App\Services;

use App\Repositories\StockOpnameRepository;

class StockOpnameService
{
    protected $opnameRepository;

    // Inject Repository ke dalam Service
    public function __construct(StockOpnameRepository $opnameRepository)
    {
        $this->opnameRepository = $opnameRepository;
    }

    public function getOpnameHistory()
    {
        return $this->opnameRepository->getAllWithRelations();
    }

    public function processOpname(array $data, $userId)
    {
        // LOGIKA BISNIS: Hitung selisih otomatis (Stok Fisik - Stok Sistem)
        $difference = $data['physical_stock'] - $data['system_stock'];

        // Siapkan data untuk disimpan
        $opnameData = [
            'product_id'     => $data['product_id'],
            'user_id'        => $userId,
            'system_stock'   => $data['system_stock'],
            'physical_stock' => $data['physical_stock'],
            'difference'     => $difference,
            'notes'          => $data['notes'] ?? null,
        ];

        // 1. Simpan data stock opname
        $opname = $this->opnameRepository->createOpname($opnameData);

        // 2. Update stok riil produk di tabel products agar sinkron dengan kondisi fisik
        $this->opnameRepository->updateProductStock($data['product_id'], $data['physical_stock'], $difference);
        return $opname;
    }
}
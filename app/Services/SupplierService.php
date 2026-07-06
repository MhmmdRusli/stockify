<?php

namespace App\Services;

use App\Repositories\SupplierRepository;

class SupplierService
{
    protected $supplierRepo;

    public function __construct(SupplierRepository $supplierRepo)
    {
        $this->supplierRepo = $supplierRepo;
    }

    public function getAllSuppliers()
    {
        return $this->supplierRepo->getAll();
    }

    public function storeSupplier(array $data)
    {
        return $this->supplierRepo->create($data);
    }

    public function getSupplierById($id)
    {
        return $this->supplierRepo->findById($id);
    }

    public function updateSupplier($id, array $data)
    {
        return $this->supplierRepo->update($id, $data);
    }

    public function deleteSupplier($id)
    {
        return $this->supplierRepo->delete($id);
    }
    
}
<?php

namespace App\Services;

use App\Repositories\StockTransactionRepository;

class StockTransactionService
{
    protected $transactionRepo;

    public function __construct(StockTransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function getAllTransactions()
    {
        return $this->transactionRepo->getAll();
    }

    public function storeTransaction(array $data)
    {
        return $this->transactionRepo->create($data);
    }
}
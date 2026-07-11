<?php

namespace App\Notifications;

use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockTransactionPendingNotification extends Notification
{
    use Queueable;

    protected StockTransaction $transaction;
    protected User $creator;

    public function __construct(StockTransaction $transaction, User $creator)
    {
        $this->transaction = $transaction;
        $this->creator = $creator;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $typeLabel = $this->transaction->type === 'in' ? 'barang masuk' : 'barang keluar';
        $productName = $this->transaction->product->name ?? 'Produk';

        return [
            'type'             => 'stock_transaction_pending',
            'transaction_id'   => $this->transaction->id,
            'product_id'       => $this->transaction->product_id,
            'product_name'     => $productName,
            'quantity'         => $this->transaction->quantity,
            'transaction_type' => $this->transaction->type, // 'in' atau 'out'
            'created_by'       => $this->creator->name,
            'message'          => "{$this->creator->name} mengajukan {$typeLabel} \"{$productName}\" sebanyak {$this->transaction->quantity} pcs. Menunggu konfirmasi Anda.",
            'url'              => $this->transaction->type === 'in' ? url('/barang-masuk') : url('/barang-keluar'),
        ];
    }
}
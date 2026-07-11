<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RestockTaskNotification extends Notification
{
    use Queueable;

    protected Product $product;
    protected User $requestedBy;

    public function __construct(Product $product, User $requestedBy)
    {
        $this->product = $product;
        $this->requestedBy = $requestedBy;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'restock_task',
            'product_id'      => $this->product->id,
            'product_name'    => $this->product->name,
            'current_stock'   => $this->product->stock,
            'minimum_stock'   => $this->product->minimum_stock,
            'requested_by'    => $this->requestedBy->name,
            'message'         => "{$this->requestedBy->name} meminta Anda restock \"{$this->product->name}\" (stok saat ini: {$this->product->stock}).",
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{

    protected $table = 'order_detail';

    protected $fillable = [
        'ID_order',
        'ID_product',
        'RequestedQuantity',
        'PriceQuantity'
    ];

    public function Orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'ID_order');
    }


    public function Products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'order';
    /**
     * Los atributos asignados en masa
     */
    protected $fillable = [
        'ID_users',
        'OrderDate'
    ];

    protected $attributes = [
        'OrderTotal' => 0
    ];

    public function Users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'ID_order');
    }

    public function AddDetails(array $details)
    {
        $total = 0;

        foreach ($details as $detail) {
            $product = Product::findOrFail($detail['ID_product']);

            if ($product->CurrentStock < $detail['RequestedQuantity']) {
                throw new \Exception("Stock Insuficiente para {$product->ProductName}");
            }

            $subtotal = $product->UnityPrice * $detail['RequestedQuantity'];

            $this->OrderDetails()->create([
                'ID_product' => $detail['ID_product'],
                'RequestedQuantity' => $detail['RequestedQuantity'],
                'PriceQuantity' => $subtotal,
            ]);

            $product->decrement('CurrentStock', $detail['RequestedQuantity']);
            $total += $subtotal;
        }
        $this->OrderTotal = $total;
        $this->save();
        $this->refresh();
        return $this;
    }
}

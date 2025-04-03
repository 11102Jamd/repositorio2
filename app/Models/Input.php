<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Input extends Model
{
    protected $table = 'inputs';

    protected $fillable = [
        'ID_purchase_order',
        'InputName',
        'UnitMeasurement',
        'InitialQuantity',
        'CurrentStock',
        'UnityPrice'
    ];

    public function PurchaseOrders(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}

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
        'InitialQuantity',
        'UnitMeasurement',
        'CurrentStock',
        'UnitMeasurementGrams',
        'UnityPrice'
    ];

    public function PurchaseOrders(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function ConvertUnit()
    {
        if ($this->UnitMeasurement == 'Kg') {
            $this->UnitMeasurementGrams = 'g';
            $this->CurrentStock *= 1000;
            return true;

        } else if ($this->UnitMeasurement == 'lb') {
            $this->UnitMeasurementGrams = 'g';
            $this->CurrentStock *= 500;
            return true;
        }
        
        return false;
    }

}

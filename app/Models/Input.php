<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Input extends Model
{
    protected $table = 'inputs';

    protected $fillable = [
        'InputName',
        'CurrentStock',
        'UnitMeasurementGrams',
        'UnityPrice'
    ];

    protected $attributes = [
        'CurrentStock' => 0,
        'UnitMeasurementGrams' => 'g',
        'UnityPrice' => 0
    ];

    public function inputOrders(): HasMany
    {
        return $this->hasMany(InputOrder::class, 'ID_input');
    }

    public function ConvertUnit($quantity, $unitMeasurement)
    {
        switch ($unitMeasurement) {
            case 'Kg':
                return $quantity * 1000;
            case 'lb':
                return $quantity * 453.592;
            default:
                return $quantity;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $table = 'recipe';

    protected $fillable = [
        'ID_manufacturing',
        'ID_inputs',
        'AmountSpent',
        'UnitMeasurement',
        'PriceQuantitySpent'
    ];
    protected $attributes = [
        'PriceQuantitySpent' => 0
    ];

    public function Manufacturing(): BelongsTo
    {
        return $this->belongsTo(Manufacturing::class, 'ID_manufacturing');
    }

    public function Input(): BelongsTo
    {
        return $this->belongsTo(Input::class, 'ID_inputs');
    }

    public function PriceQuantitySpent()
    {
        $input = $this->Input;

        if (!$input) {
            throw new \Exception("El insumo asociado no existe.");
        }

        $AmountSpent = $this->AmountSpent;

        if ($input->UnitMeasurement == 'Kg') {
            $AmountSpent = $AmountSpent / 1000;
        } elseif ($input->UnitMeasurement == 'lb') {
            $AmountSpent = $AmountSpent / 453.592;
        }

        $PriceQuantitySpent = $AmountSpent * $input->UnityPrice;

        return round($PriceQuantitySpent, 2);
    }

    public function RestoreStockInputs()
    {
        $input = $this->Input;

        if ($input) {
            $input->increment('CurrentStock', $this->AmountSpent);
        }
    }
}

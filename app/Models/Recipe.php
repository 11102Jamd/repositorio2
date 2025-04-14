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
        'PriceQuantitySpent' => 0,
        'UnitMeasurement' => 'g'
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

        $inputOrder = $this->Input->inputOrders()->first();

        if (!$inputOrder) {
            throw new \Exception("No se encontro el insumo con su orden de compra");
        }

        $amountInOrderUnit = $this->AmountSpent;

        if ($inputOrder->UnitMeasurement === 'Kg') {
            $amountInOrderUnit = $this->AmountSpent / 1000;
        } elseif ($inputOrder->UnitMeasurement === 'lb') {
            $amountInOrderUnit = $this->AmountSpent / 453.592;
        }

        return round($amountInOrderUnit * $input->UnityPrice, 2);
    }

    public function RestoreStockInputs()
    {
        $input = $this->Input;

        if ($input) {
            $input->increment('CurrentStock', $this->AmountSpent);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_order';

    protected $fillable = [
        'ID_supplier',
        'PurchaseOrderDate',
        'PurchaseTotal'
    ];

    protected $attributes = [
        'PurchaseTotal' => 0
    ];

    public function Supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function inputOrders(): HasMany
    {
        return $this->hasMany(InputOrder::class, 'ID_purchase_order');
    }

    public function AddInputs(array $inputsData)
    {

        $total = 0;
        $createdInputOrders = [];

        foreach ($inputsData as $inputData) {

            $input = Input::findOrFail($inputData['ID_input']);

            $grams = $input->ConvertUnit(
                $inputData['InitialQuantity'],
                $inputData['UnitMeasurement']
            );

            $input->increment('CurrentStock', $grams);

            $input->InitialQuantity = $inputData['InitialQuantity'];
            $input->UnitMeasurement = $inputData['UnitMeasurement'];
            $input->UnityPrice = $inputData['UnityPrice'];
            $input->save();

            $subtotal = $inputData['InitialQuantity'] * $inputData['UnityPrice'];

            $inputOrder = $this->inputOrders()->create([
                'ID_input' => $input->id,
                'PriceQuantity' => $subtotal
            ]);

            $total += $subtotal;
            $createdInputOrders[] = $inputOrder;
        }

        $this->PurchaseTotal = $total;
        $this->save();

        return [
            'order' =>  $this->fresh()->load('inputOrders.input'),
            'input_orders' => $createdInputOrders
        ];
    }
}

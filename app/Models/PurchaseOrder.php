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

    public function Inputs(): HasMany
    {
        return $this->hasMany(Input::class, 'ID_purchase_order');
    }

    public function AddInputs(array $inputsData)
    {

        $total = 0;
        $createdInputs = [];
        foreach ($inputsData as $inputData) {
            $unitMeasurementGrams = $inputData['UnitMeasurementGrams'] ?? null;

            $input = new Input([
                'InputName' => $inputData['InputName'],
                'InitialQuantity' => $inputData['InitialQuantity'],
                'UnitMeasurement' => $inputData['UnitMeasurement'],
                'CurrentStock' => $inputData['CurrentStock'],
                'UnitMeasurementGrams' => $unitMeasurementGrams,
                'UnityPrice' => $inputData['UnityPrice']
            ]);

            $input->ConvertUnit();
            $this->Inputs()->save($input);

            $total += $inputData['InitialQuantity'] * $inputData['UnityPrice'];

            $createdInputs[] = $input;
        }
        $this->update(['PurchaseTotal' => $total]);

        return [
            'order' => $this->fresh(),
            'inputs' => $createdInputs
        ];
    }
}

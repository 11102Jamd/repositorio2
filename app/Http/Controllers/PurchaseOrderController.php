<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends BaseCrudController
{
    protected $model = PurchaseOrder::class;
    protected $validationRules = [
        'ID_supplier' => 'required|exists:supplier,id',
        'PurchaseOrderDate' => 'required|date',
        'OrderTotal' => 'sometimes|numeric|min:0',
        'inputs' => 'required|array|min:1',
        'inputs.*.InputName' => 'required|string|max:100',
        'inputs.*.UnitMeasurement' => 'required|string|max:20',
        'inputs.*.InitialQuantity' => 'required|numeric|min:0',
        'inputs.*.CurrentStock' => 'required|numeric|min:0',
        'inputs.*.UnityPrice' => 'required|numeric|min:0'
    ];

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $order = $this->model::create([
                'ID_supplier' => $validatedData['ID_supplier'],
                'PurchaseOrderDate' => $validatedData['PurchaseOrderDate']
            ]);

            $order->AddInputs($validatedData['inputs']);
            return response()->json([
                'Message' => "pedido creado exitosamente",
                'OrdenCompra' => $order->fresh()->load('inputs')
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }
}

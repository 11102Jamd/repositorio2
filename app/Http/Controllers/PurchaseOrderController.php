<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends BaseCrudController
{
    protected $model = PurchaseOrder::class;
    protected $validationRules = [
        'ID_supplier' => 'required|exists:supplier,id',
        'PurchaseOrderDate' => 'required|date',
        'inputs' => 'required|array|min:1',
        'inputs.*.ID_input' => 'required|exists:inputs,id',
        'inputs.*.InitialQuantity' => 'required|numeric|min:0',
        'inputs.*.UnitMeasurement' => 'required|string|in:g,Kg,lb',
        'inputs.*.UnityPrice' => 'required|numeric|min:0'
    ];

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $this->validateRequest($request);

            $purchaseOrder = $this->model::create([
                'ID_supplier' => $validatedData['ID_supplier'],
                'PurchaseOrderDate' => $validatedData['PurchaseOrderDate']
            ]);

            $result = $purchaseOrder->AddInputs($validatedData['inputs']);

            DB::commit();

            return response()->json([
                'Message' => "Orden de compra creada exitosamente",
                'OrdenCompra' => $result['order'],
                'Insumos' => $result['input_orders']
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error en PurchaseOrder@Controller: " . $th->getMessage());
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }
}

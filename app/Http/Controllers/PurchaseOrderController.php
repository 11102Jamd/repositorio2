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
        DB::beginTransaction();
        try {
            $validatedData = $this->validateRequest($request);

            $order = $this->model::create([
                'ID_supplier' => $validatedData['ID_supplier'],
                'PurchaseOrderDate' => $validatedData['PurchaseOrderDate']
            ]);

            $order->AddInputs($validatedData['inputs']);

            DB::commit();
            
            return response()->json([
                'Message' => "pedido creado exitosamente",
                'OrdenCompra' => $order->fresh()->load('inputs')
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

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends BaseCrudController
{
    protected $model = Order::class;
    protected $validationRules = [
        'ID_users' => 'required|integer|exists:users,id',
        'OrderDate' => 'required|date',
        'OrderTotal' => 'sometimes|numeric|min:0',
        'details' => 'required|array|min:1',
        'details.*.ID_product' => 'required|exists:product,id',
        'details.*.RequestedQuantity' => 'required|integer|min:1'
    ];

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $order = $this->model::create([
                'ID_users' => $validatedData['ID_users'],
                'OrderDate' => $validatedData['OrderDate'],
            ]);

            $order->AddDetails($validatedData['details']);

            return response()->json([
                'Message' => "pedido creado exitosamente",
                'pedido' => $order->fresh()->load('OrderDetails')
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }
}

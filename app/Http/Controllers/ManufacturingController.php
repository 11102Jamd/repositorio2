<?php

namespace App\Http\Controllers;

use App\Models\Manufacturing;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManufacturingController extends BaseCrudController
{
    protected $model = Manufacturing::class;
    protected $validationRules = [
        'ID_product' => 'required|exists:product,id',
        'ManufacturingTime' => 'required|integer|min:1',
        'recipes' => 'required|array|min:1',
        'recipes.*.ID_inputs' => 'required|exists:inputs,id',
        'recipes.*.AmountSpent' => 'required|numeric|min:0.01',
        'recipes.*.UnitMeasurement' => 'required|string|in:g,kg,lb'
    ];

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $this->validateRequest($request);

            $manufacturing = $this->model::create([
                'ID_product' => $validatedData['ID_product'],
                'ManufacturingTime' => $validatedData['ManufacturingTime']
            ]);

            $manufacturing->CalculateLabour();

            $manufacturing->AddIngredients($validatedData['recipes']);

            DB::commit();

            return response()->json([
                'Message' => "fabricacion creado exitosamente",
                'Fabricacion' => $manufacturing->fresh()->load('recipes')
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error en Manufacturing@Controller: " . $th->getMessage());
            return response()->json([
                'error' => 'Datos inválidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $manufacturing = Manufacturing::with('recipes')->findOrFail($id);

            foreach ($manufacturing->recipes as $recipe) {
                $recipe->RestoreStockInputs();
            }

            $manufacturing->recipes()->delete();
            $manufacturing->delete();

            DB::commit();
            return response()->json([
                'message' => "Fabricación eliminada exitosamente",
                'stock' => true
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => "Error al eliminar fabricación",
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}

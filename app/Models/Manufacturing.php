<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturing extends Model
{
    protected $table = 'manufacturing';

    protected $fillable = [
        'ID_product',
        'ManufacturingTime',
        'Labour',
        'ManufactureProductG',
        'TotalCostProduction',
    ];

    protected $attributes = [
        'Labour' => 0,
        'ManufactureProductG' => 0,
        'TotalCostProduction' => 0
    ];

    public function Products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'ID_manufacturing');
    }

    public function CalculateLabour()
    {
        if ($this->ManufacturingTime != 0) {
            $timeInHours = $this->ManufacturingTime / 60;
            $this->Labour = $timeInHours * 10000;
            $this->save();
        }

        return $this;
    }

    public function AddIngredients(array $recipes)
    {
        $total = 0;
        $totalGrams = 0;
        foreach ($recipes as $recipe) {
            $input = Input::findOrFail($recipe['ID_inputs']);

            if ($input->CurrentStock < $recipe['AmountSpent']) {
                throw new \Exception("Stock insuficiente para {$input->InputName}");
            }

            $recipeModel = $this->recipes()->create([
                'ID_inputs' => $input->id,
                'AmountSpent' => $recipe['AmountSpent'],
                'UnitMeasurement' => 'g'
            ]);

            $subtotal = $recipeModel->PriceQuantitySpent();
            $totalGrams += $recipe['AmountSpent'];

            $recipeModel->update(['PriceQuantitySpent' => $subtotal]);

            $input->decrement('CurrentStock', $recipe['AmountSpent']);
            $total += $subtotal;
        }

        $this->TotalCostProduction = $total + $this->Labour;
        $this->ManufactureProductG = $totalGrams;
        $this->save();

        return $this->fresh()->load('recipes.Input');
    }
}

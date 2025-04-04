<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;

class InputController extends BaseCrudController
{
    protected $model = Input::class;
    protected $validationRules = [
        'ID_purchase_order'=>'required|exists:purchase_order,id',
        'InputName'=>'required|string|max:50',
        'InitialQuantity'=>'required|integer|max:10',
        'UnitMeasurement'=>'required|string|max:2',
        'CurrentStock'=>'required|numeric|min:0',
        'UnitMeasurementGrams'=>'required|string|max:1',
        'UnityPrice'=>'required|numeric|min:0'
    ];
}

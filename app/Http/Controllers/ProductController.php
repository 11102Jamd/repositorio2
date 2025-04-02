<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseCrudController
{
    protected $model = Product::class;
    protected $validationRules = [
        'ProductName' => 'required|string|max:60',
        'InitialQuantity' => 'required|integer|min:1',
        'CurrentStock' => 'required|integer|min:1',
        'UnityPrice' => 'required|numeric|min:0',
    ];
}

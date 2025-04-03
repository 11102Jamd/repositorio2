<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends BaseCrudController
{
    protected $model = Supplier::class;

    public $validationRules = [
        'name' => 'required|string|max:50',
        'email'=> 'required|email|unique:users,email',
        'Addres' => 'required|string|max:255',
        'Phone'=> 'required|string|max:10'
    ];
}

<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends BaseCrudController
{
    protected $model = Supplier::class;

    public $validationRules = [
        'name' => 'required|string|max:50',
        'email'=> 'required|email|unique:supplier,email',
        'Addres' => 'required|string|max:255',
        'Phone'=> 'required|string|max:10'
    ];

    public function update(Request $request, $id)
    {
        $this->validationRules['email'] = 'required|email|unique:supplier,email,'.$id;
        return parent::update($request, $id);
    }
}

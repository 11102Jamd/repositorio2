<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;

class InputController extends BaseCrudController
{
    protected $model = Input::class;
    protected $validationRules = [
        'InputName'=>'required|string|max:50',
    ];

    public function index(){
        $inputs = Input::with(['inputOrders' => function($query){
            $query->latest()->get()->take(1);
        }])->OrderBy('id','desc')->get();

        return response()->json($inputs);
    }
}

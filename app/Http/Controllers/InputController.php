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
}

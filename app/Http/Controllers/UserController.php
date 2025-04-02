<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseCrudController
{
    protected $model = User::class;
    protected $validationRules = [
        'name1' => 'required|string|max:255',
        'name2' => 'nullable|string|max:255',
        'surname1' => 'required|string|max:255',
        'surname2' => 'nullable|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'rol' => 'required|string'
    ];
}

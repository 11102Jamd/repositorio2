<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BaseCrudController extends Controller
{
    protected $model;
    protected $validationRules = [];

    /**
     *Creamos el metodo Index en baseCrudcontroller que muestra todos los datos reegistrados
     */
    public function index()
    {
        try {
            return response()->json($this->model::all());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Registros no encontrado',
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Creamos el metodo show que muestra un regsitro esfpecifico con sus datos por medio del id
     */
    public function show($id)
    {
        try {
            $record = $this->model::findOrFail($id);
            return response()->json($record);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Registro no encontrado',
                'message' => $th->getMessage(),
            ], 404);
        }
    }


    /**
     * Creamos el metodo store que realiza un nuevo registro en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $record = $this->model::create($validatedData);
            return response()->json($record, 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Datos invalidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }


    /**
     * Creamso el metodo Update que actualiza uno o mas campo de un regsitro creado
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $record = $this->model::findOrFail($id);
            $record->update($validatedData);
            return response()->json($record);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Datos  Invalidos',
                'message' => $th->getMessage(),
            ], 422);
        }
    }


    /**
     * Creamos el metodo destroy que elimina un registro de la base de datos
     */
    public function destroy($id)
    {
        try {
            $record = $this->model::findOrFail($id);
        $record->delete();
        return response()->json(['message' => 'Registro eliminado']);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Registro No encontrado',
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    protected function validateRequest(Request $request)
    {
        if (empty($this->validationRules)) {
            throw ValidationException::withMessages(['error' => 'Reglas de validación no definidas en el controlador hijo']);
        }
        return $request->validate($this->validationRules);
    }
}

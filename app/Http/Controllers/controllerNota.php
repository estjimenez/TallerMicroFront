<?php

namespace App\Http\Controllers;

use App\Models\modelNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class controllerNota extends Controller
{
    public function index()
    {
        $nota = modelNota::all();

        if ($nota->isEmpty()) {
            $data = [
                'mensaje' => 'No se encontraron estudiantes',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        return response()->json($nota, 200);
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'actividad' => 'required',
            'nota' => 'required|numeric',
            'codEstudiante' => 'required|exists:estudiantes,cod'
        ]);

        $validator->after(function ($validator) use ($request) {
            $cantidadNotas = modelNota::where('codEstudiante', $request->codEstudiante)->count();

            if ($cantidadNotas >= 2) {
                $validator->errors()->add('codEstudiante', 'El estudiante ya tiene el maximo de dos notas registradas.');
            }
        });

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $nota = modelNota::create([
            'actividad' => $request->actividad,
            'nota' => $request->nota,
            'codEstudiante' => $request->codEstudiante
        ]);

        if (!$nota) {
            $data = [
                'message' => 'Error al crear el registro',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'nota' => $nota,
            'status' => 201
        ];
        return response()->json($data, 201);
    }
}

<?php

namespace App\Http\Controllers;


use App\Models\modelEstudiante;
use App\Models\modelNota;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class controllerEstudiante extends Controller
{
    public function index()
    {
        $estudiantes = modelEstudiante::all();

        if ($estudiantes->isEmpty()) {
            $data = [
                'mensaje' => 'No se encontraron estudiantes',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        return response()->json($estudiantes, 200);
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'cod' => 'required|unique:estudiantes',
            'nombres' => 'required|max:255',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student = modelEstudiante::create([
            'cod' => $request->cod,
            'nombres' => $request->nombres,
            'email' => $request->email
        ]);

        if (!$student) {
            $data = [
                'message' => 'Error al crear estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($cod)
    {
        $student = modelEstudiante::find($cod);
    
        
        if (!$student) {
            return response()->json([
                'mensaje' => 'Estudiante no encontrado',
                'status' => 404
            ], 404);
        }
    
    
        $notas = modelNota::where('codEstudiante', $cod)->get();
    
     
        if ($notas->isEmpty()) {
            return response()->json([
                'cod' => $student->cod,
                'nombre' => $student->nombres,
                'email' => $student->email,
                'notaDefinitiva' => 'Sin registrar',
                'estado' => 'Sin registrar',
                'status' => 200
            ], 200);
        }
    
      
        if (!$notas->every(fn($nota) => is_numeric($nota->nota))) {
            return response()->json([
                'mensaje' => 'Error: Las notas no son válidas',
                'status' => 500
            ], 500);
        }
    
     
        $sumaNotas = $notas->sum('nota');
        $promedio = $sumaNotas / $notas->count();
    
        $estado = $promedio >= 3 ? 'Aprobado' : 'Reprobado';
    
      
        return response()->json([
            'cod' => $student->cod,
            'nombre' => $student->nombres,
            'email' => $student->email,
            'notaDefinitiva' => number_format($promedio, 2), 
            'estado' => $estado,
            'status' => 200
        ], 200);
    }
    


    public function destroy($cod)
    {
        $student = modelEstudiante::find($cod);
    
        if (!$student) {
            $data = [
                'mensaje' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
    
        modelNota::where('codEstudiante', $cod)->delete();
    
        $student->delete();
    
        $data = [
            'mensaje' => 'Estudiante y sus notas eliminados',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $cod)
    {
        $student = modelEstudiante::find($cod);

        if (!$student) {
            $data = [
                'messague' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator =  Validator::make($request->all(), [
            'nombres' => 'required|max:255',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $student->nombres = $request->nombres;
        $student->email = $request->email;

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
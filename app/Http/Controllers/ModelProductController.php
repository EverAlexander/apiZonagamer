<?php

namespace App\Http\Controllers;

use App\Models\ModelProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelProductController extends Controller
{
    //
    public function select(Request $request)
    {
        try {
            $query = ModelProduct::with('marcaInfo'); // ← incluye la relación

            // Buscar si se envía search
            if ($request->has('search') && !empty($request->search)) {
                $s = $request->search;

                $query->where('nombre', 'LIKE', "%$s%")
                    ->orWhere('modelo', 'LIKE', "%$s%");
            }

            //Comentario de prueba

            $modelos = $query->get();

            // Mapear para enviar un campo 'marcaNombre' directamente
            $modelos = $query->get()->map(function ($item) {
                return [
                    'id_modelo' => $item->id_modelo,
                    'nombre' => $item->nombre,
                    'modelo' => $item->modelo,
                    'marca' => $item->marca,
                    'marcaNombre' => $item->marcaInfo ? $item->marcaInfo->nombre : ''
                ];
            });

            return response()->json([
                'code' => 200,
                'data' => $modelos,
                'total' => $modelos->count()
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        //Validar los datos que sean requeridos
        try {
            //Log::info($request->all());
            $validation = Validator::make($request->all(), [
                'nombre' => 'required',
                'modelo' => 'required',
                'marca'  => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $brands = ModelProduct::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Producto Registrado'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}

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
                    'descripcion' => $item->descripcion,
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
                'descripcion' => 'required',
                'modelo' => 'required',
                'marca'  => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $modelo = ModelProduct::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Producto Registrado'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $id_marca)
    {
        //Validar los datos que sean requeridos
        try {
            $validation = Validator::make($request->all(), [
                'nombre' => 'required',
                'descripcion' => 'required',
                'modelo' => 'required',
                'marca'  => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $modelo = ModelProduct::find($id_marca);
                if ($modelo) {
                    $modelo->update($request->all());
                    return response()->json([
                        'code' => 200,
                        'data' => 'Marca Actualizada'
                    ], 200);
                } else {
                    return response()->json([
                        'code' => 404,
                        'data' => 'No se encontro Marca'
                    ], 404);
                }
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $modelo = ModelProduct::find($id);
            if ($modelo) {
                $modelo->delete($id);
                return response()->json([
                    'code' => 200,
                    'data' => 'Producto eliminado'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Producto no encontrado'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}

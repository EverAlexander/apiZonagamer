<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

use Log;

class CategoriaController extends Controller
{
    //

    public function select()
    {
        try {
            $categoria = Categoria::all();
            if ($categoria->count() > 0) {
                return response()->json([
                    'code' => 200,
                    'data' => $categoria
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'No hay categoria registradas'
                ], 404);
            }
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
                'nombrecate' => 'required',
                'descripcion' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $categoria = Categoria::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Categoria Registrada'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        //Validar los datos que sean requeridos
        try {
            $validation = Validator::make($request->all(), [
                'nombrecate' => 'required',
                'descripcion' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $categoria = Categoria::find($id);
                if ($categoria) {
                    $categoria->update($request->all());
                    return response()->json([
                        'code' => 200,
                        'data' => 'Categoria Actualizada'
                    ], 200);
                } else {
                    return response()->json([
                        'code' => 404,
                        'data' => 'No se encontro categoria'
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
            $categoria = Categoria::find($id);
            if ($categoria) {
                $categoria->delete($id);
                return response()->json([
                    'code' => 200,
                    'data' => 'Categoria eliminada'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Categoria no encontrada'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function find($id)
    {
        $categoria = Categoria::find($id);
        if ($categoria) {
            $datos = Categoria::select()
                ->where('idCat', $id)->get();
            return response()->json([
                'code' => 200,
                'data' => $datos[0]
            ], 200);
        } else {
            return response()->json([
                'code' => 404,
                'data' => 'Registro no encontrado'
            ], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    //
    public function select(Request $request)
    {
        try {

            $query = Brands::query();

            // si viene un texto de búsqueda
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;

                $query->where('nombre', 'LIKE', "%$search%")
                    ->orWhere('descripcion', 'LIKE', "%$search%");
            }

            $brands = $query->get();

            return response()->json([
                'code' => 200,
                'data' => $brands,
                'total' => $brands->count()
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
                'descripcion' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $brands = Brands::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Categoria Registrada'
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
                'descripcion' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $brands = Brands::find($id_marca);
                if ($brands) {
                    $brands->update($request->all());
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
            $brands = Brands::find($id);
            if ($brands) {
                $brands->delete($id);
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
        $brands = Brands::find($id);
        if ($brands) {
            $datos = Brands::select()
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

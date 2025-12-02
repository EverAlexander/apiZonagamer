<?php

namespace App\Http\Controllers;

use App\Models\EstadoInventario;
use App\Models\GameConsole;
use App\Models\Ubicaciones;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GameConsoleController extends Controller
{
    //
    public function select(Request $request)
    {
        try {
            // Cargar relaciones
            $query = GameConsole::with(['versionInfo', 'estadoInfo', 'ubicacionInfo']);

            // Buscar si se envía "search"
            if ($request->filled('search')) {
                $s = $request->search;

                $query->where(function ($q) use ($s) {
                    $q->where('numero_serie', 'LIKE', "%$s%")
                        ->orWhere('activofijo', 'LIKE', "%$s%");
                });
            }

            // Obtener datos
            $consolas = $query->get()->map(function ($item) {
                return [
                    'numero_serie' => $item->numero_serie,
                    'activofijo' => $item->activofijo,
                    'id_modelo' => $item->id_modelo,
                    'color' => $item->color,
                    'observacion' => $item->observacion,
                    'id_estado' => $item->id_estado,
                    'id_ubicacion' => $item->id_ubicacion,

                    // Datos de relaciones
                    'modelo' => $item->versionInfo?->modelo ?? null,
                    'estado' => $item->estadoInfo?->nombre ?? null,
                    'ubicacion' => $item->ubicacionInfo?->nombre ?? null,
                ];
            });

            return response()->json([
                'code' => 200,
                'data' => $consolas,
                'total' => $consolas->count()
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    //Obtener ubicaciones
    public function selectUbicacion()
    {
        try {
            $ubicaciones = Ubicaciones::all();
            if ($ubicaciones->count() > 0) {
                return response()->json([
                    'code' => 200,
                    'data' => $ubicaciones
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'No hay ubicaciones registradas'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    //Obtener estados
    public function selectEstados()
    {
        try {
            $estadosV = EstadoInventario::all();
            if ($estadosV->count() > 0) {
                return response()->json([
                    'code' => 200,
                    'data' => $estadosV
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'No hay estados registrados'
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
                'numero_serie' => 'required',
                'activofijo' => 'required',
                'id_modelo' => 'required',
                'color'  => 'required',
                'observacion'  => 'required',
                'id_estado'  => 'required',
                'id_ubicacion'  => 'required',
                
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $modelo = GameConsole::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Consola Registrada'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $serie)
    {
        //Validar los datos que sean requeridos
        try {
            $validation = Validator::make($request->all(), [
                'numero_serie' => 'required',
                'activofijo' => 'required',
                'id_modelo' => 'required',
                'color'  => 'required',
                'observacion'  => 'required',
                'id_estado'  => 'required',
                'id_ubicacion'  => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $consola = GameConsole::find($serie);
                if ($consola) {
                    $consola->update($request->all());
                    return response()->json([
                        'code' => 200,
                        'data' => 'Consola Actualizada'
                    ], 200);
                } else {
                    return response()->json([
                        'code' => 404,
                        'data' => 'No se encontro la consola'
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
            $consola = GameConsole::find($id);
            if ($consola) {
                $consola->delete($id);
                return response()->json([
                    'code' => 200,
                    'data' => 'Consola eliminada'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Consola no encontrado'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}

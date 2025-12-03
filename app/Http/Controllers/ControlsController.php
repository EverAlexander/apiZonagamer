<?php

namespace App\Http\Controllers;

use App\Models\Controls;
use App\Models\EstadoInventario;
use App\Models\Ubicaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ControlsController extends Controller
{
    //
    public function select(Request $request)
    {
        try {
            // Cargar relaciones
            $query = Controls::with(['versionInfo', 'estadoInfo', 'ubicacionInfo']);

            // Buscar si se envía "search"
            if ($request->filled('search')) {
                $s = $request->search;

                $query->where(function ($q) use ($s) {
                    $q->where('numero_serie', 'LIKE', "%$s%")
                        ->orWhere('descripcion', 'LIKE', "%$s%");
                });
            }

            // Obtener datos
            $control = $query->get()->map(function ($item) {
                return [
                    'id_control' => $item->id_control,
                    'descripcion' => $item->descripcion,
                    'modelo' => $item->modelo,
                    'color' => $item->color,
                    'numero_serie' => $item->numero_serie,
                    'observacion' => $item->observacion,
                    'id_estado' => $item->id_estado,
                    'id_ubicacion' => $item->id_ubicacion,
                    'fechaActualizacion' =>$item->fechaActualizacion,

                    // Datos de relaciones
                    'nombremodelo' => $item->versionInfo?->modelo ?? null,
                    'modproducto' => $item->versionInfo?->nombre ?? null,
                    'modDescrip' => $item->versionInfo?->descripcion ?? null,
                    'estado' => $item->estadoInfo?->nombre ?? null,
                    'ubicacion' => $item->ubicacionInfo?->nombre ?? null,
                ];
            });

            return response()->json([
                'code' => 200,
                'data' => $control,
                'total' => $control->count()
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
                'numero_serie' => 'required',
                'descripcion' => 'required',
                'modelo' => 'required',
                'color'  => 'required',
                'id_estado'  => 'required',
                'id_ubicacion'  => 'required',
                'observacion'  => 'required',
                'fechaActualizacion'  => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $control = Controls::create($request->all());
                return response()->json([
                    'code' => 200,
                    'data' => 'Control Registrada'
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
                'descripcion' => 'required',
                'modelo' => 'required',
                'color'  => 'required',
                'id_estado'  => 'required',
                'id_ubicacion'  => 'required',
                'observacion'  => 'required',
                'fechaActualizacion'  => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'code' => 400,
                    'data' => $validation->messages()
                ], 400);
            } else {
                $control = Controls::find($serie);
                if ($control) {
                    $control->update($request->all());
                    return response()->json([
                        'code' => 200,
                        'data' => 'Controles Actualizada'
                    ], 200);
                } else {
                    return response()->json([
                        'code' => 404,
                        'data' => 'No se encontro control asociado'
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
            $control = Controls::find($id);
            if ($control) {
                $control->delete($id);
                return response()->json([
                    'code' => 200,
                    'data' => 'Control eliminado'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'No encontrado'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    //Obtener ubicaciones
    public function selectUbication()
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
}

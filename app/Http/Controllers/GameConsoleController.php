<?php

namespace App\Http\Controllers;

use App\Models\GameConsole;
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
}

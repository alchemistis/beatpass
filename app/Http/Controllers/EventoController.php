<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function all()
    {
        $eventos = Evento::all();
        return response()->json($eventos);
    }

    public function show($slug)
    {
        $evento = Evento::with('ingressos')
            ->with('imagens')
            ->where('slug', $slug)
            ->first();

        if ($evento === null)
        {
            return response('ID do evento inválido.', 404);
        }

        return response()->json($evento);
    }
}

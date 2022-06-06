<?php


namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

use App\Models\Ingresso;

class IngressoController extends Controller
{
    public function show($idEvento)
    {
        $ingressos = Ingresso::where('idevento', $idEvento)->get();

        return response()->json($ingressos);
    }
}

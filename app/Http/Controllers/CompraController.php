<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompraController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function show($token)
    {
        $authUser = Auth::user();

        $compra = Compra::with('ingresso')
            ->with('evento')
            ->where('codvalidacao', $token)->first();

        if ($compra === null) {
            return response('Token inválido.', 404);
        }

        return response()->json($compra);
    }

    public function all($id)
    {
        $authUser = Auth::user();

        $compras = Compra::with('ingresso')
            ->with('evento')
            ->where('iduser', $id)->get();

        return response()->json($compras);
    }
}
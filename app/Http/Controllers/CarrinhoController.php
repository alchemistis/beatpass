<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Ingresso;
use App\Models\User;
use App\Models\Carrinho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CarrinhoController extends Controller
{
    public function __construct()
    {
        $this->middleware('basic.auth');
    }

    public function show($id)
    {
        $authUser = Auth::user();

        if ($authUser->id != $id)
        {
            return response('', 403);
        }

        $carrinho = Carrinho::with('ingresso')
            ->with('evento')
            ->where('idusr', $id)->get();

        if ($carrinho === null)
        {
            return response('Carrinho inválido.', 404);
        }

        return response()->json($carrinho);
    }

    public function addProduct(Request $request, $id)
    {
        $authUser = Auth::user();

        if ($authUser->id != $id)
        {
            return response('', 403);
        }

        $ingresso = Ingresso::where('id', $request->idingresso)->first();

        if ($ingresso === null)
        {
            return response('Ingresso inválido.', 401);
        }

        $newCarrinho = new Carrinho();
        $newCarrinho->idusr = $id;
        $newCarrinho->idingresso = $request->idingresso;
        $newCarrinho->qtd = $request->qtd;

        if ($newCarrinho->save() != null)
        {
            $carrinhoCompleto = Carrinho::where('idusr', $id)
                ->get();

            return response()->json($carrinhoCompleto);
        }

        return response('', 500);
    }

    public function removeProduct(Request $request, $id)
    {
        $authUser = Auth::user();

        if ($authUser->id != $id)
        {
            return response('', 403);
        }

        $this->validate($request, [
           'ingresso' => 'required'
        ]);

        $ingresso = $request->ingresso;

        $carrinho = Carrinho::where('idusr', $id)
            ->where('idingresso', $ingresso)
            ->first();

        if ($carrinho === null)
        {
            return response('Carrinho inválido.', 404);
        }

        $carrinho->delete();
    }

    public function checkout(Request $request, $id)
    {
        $authUser = Auth::user();

        if ($authUser->id != $id)
        {
            return response('', 403);
        }

        $carrinho = Carrinho::where('idusr', $id)
            ->get();

        if ($carrinho === null)
        {
            return response('Carrinho inválido.', 404);
        }

        $tokenArray = [];

        foreach ($carrinho as $item)
        {
            // Tira 1 do ingresso
            $ingresso = Ingresso::where('id', $item->idingresso)->first();
            
            for ($i = 0; $i < $item->qtd; $i++)
            {
                if($ingresso->estoquedisponivel > 0)
                {
                    $tokenMd5 = md5(uniqid(rand(), true));
                    array_push($tokenArray, $tokenMd5);

                    $compra = new Compra();
                    $compra->codvalidacao = $tokenMd5;
                    $compra->iduser = $id;
                    $compra->idingresso = $item->idingresso;
                    $compra->datacompra = date('Y-m-d H:i:s');
                    $compra->fpagamento = 1;
                    $compra->status = 'Pago';

                    $compra->save();

                    $ingresso->estoquedisponivel--;
                    $ingresso->save();
                }
                else
                {
                    return response('Não há ingressos disponíveis.', 404);
                }
            }

            Carrinho::destroy($item->id);
        }

        return response()->json($tokenArray);
    }
}

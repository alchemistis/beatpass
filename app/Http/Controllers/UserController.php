<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function show($id)
    {
        $authUser = Auth::user();

        $user = User::with('endereco')->where('id', $id)->first();

        if ($user === null)
        {
            return response('ID do usuário inválido.', 404);
        }

        if ($authUser->id != $user->id)
        {
            return response('', 403);
        }

        return response()->json($user);
    }

    public function authenticate(Request $request)
    {
        try
        {
            $user = User::where('email', $request->email)->first();

            if($user == null)
            {
                return response('Usuário não existe.', 404);
            }

            if(!password_verify($request->senha, $user->senha))
            {
                return response('Senha incorreta.', 401);
            }
            else
            {
                return response()->json($user);
            }
        }
        catch(\Throwable $ex)
        {
            return response('Deu merda', 500);
        }
    }

    public function create(Request $request)
    {
        try
        {
            $messages = [
                'required' => 'O campo :attribute deve ser preenchido.',
                'size' => 'O tamanho do campo :attribute deve ser exatamente :size.',
                'max' => 'O tamanho do campo :atrribute deve ser no máximo :max.',
                'datanas.date_format' => 'A data de nascimento deve estar formatada corretamente.',
                'email.unique' => 'Este email já está em uso.'
            ];

            $validator = Validator::make($request->all(), [
                'nome' => 'required',
                'cpf' => 'required|size:11',
                'rg' => 'required|max:14',
                'datanas' => 'required|date_format:d-m-Y',
                'telefone' => 'required|max:13',
                'sexo' => 'required',
                'email' => 'required|unique:usuario',
                'senha' => 'required|max:129',
                'cep' => 'required',
                'rua' => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'estado' => 'required',
            ], $messages);

            $validator->validate();
        }
        catch (ValidationException $e)
        {
            return response()->json($e->errors(), 400);
        }

        $hash = password_hash($request->senha, PASSWORD_BCRYPT);

        $user = new User();
        $user->nome = $request->nome;
        $user->cpf = $request->cpf;
        $user->rg = $request->rg;
        $user->datanasc = date("Y-m-d", strtotime($request->datanas));;
        $user->telefone = $request->telefone;
        $user->sexo = $request->sexo;
        $user->email = $request->email;
        $user->senha = $hash;
        $user->tipo = 'Cliente';

        $endereco = new Endereco();
        $endereco->cep = $request->cep;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->complemento = $request->complemento;
        $endereco->bairro = $request->bairro;
        $endereco->cidade = $request->cidade;
        $endereco->uf = $request->estado;

        //$user->endereco = $endereco;

        $user->save();
        $user->endereco()->save($endereco);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $authUser = Auth::user();

        try
        {
            $user = User::where('id', $id)->first();

            if($user == null)
            {
                return response('Usuário inválido.', 401);
            }

            $messages = [
                'required' => 'O campo :attribute deve ser preenchido.',
                'size' => 'O tamanho do campo :attribute deve ser exatamente :size.',
                'max' => 'O tamanho do campo :atrribute deve ser no máximo :max.',
                'datanas.date_format' => 'A data de nascimento deve estar formatada corretamente.',
                'email.unique' => 'Este email já está em uso.'
            ];

            $validator = Validator::make($request->all(), [
                'nome' => 'required',
                'cpf' => 'required|size:11',
                'rg' => 'required|max:14',
                'datanas' => 'required|date_format:d-m-Y',
                'telefone' => 'required|max:13',
                'sexo' => 'required',
                'email' => 'required|unique:usuario',
                'senha' => 'required|max:129',
                'cep' => 'required',
                'rua' => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'estado' => 'required',
            ], $messages);

            //$validator->validate();
        }
        catch (ValidationException $e)
        {
            return response()->json($e->errors(), 400);
        }

        $hash = password_hash($request->senha, PASSWORD_BCRYPT);

        $user->nome = $request->nome;
        $user->cpf = $request->cpf;
        $user->rg = $request->rg;
        $user->datanasc = date("Y-m-d", strtotime($request->datanas));
        $user->telefone = $request->telefone;
        $user->sexo = $request->sexo;
        $user->email = $request->email;
        $user->senha = $hash;
        $user->tipo = 'Cliente';

        $endereco = $user->endereco;
        $endereco->cep = $request->cep;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->complemento = $request->complemento;
        $endereco->bairro = $request->bairro;
        $endereco->cidade = $request->cidade;
        $endereco->uf = $request->estado;

        $user->save();
        $user->endereco()->save($endereco);

        return response()->json($user);
    }

    public function delete($id)
    {
        $authUser = Auth::user();

        if ($authUser->id != $id)
        {
            return response('', 403);
        }

        $user = User::find($id);

        $user->endereco()->delete();
        $user->carrinho()->delete();
        $user->compras()->delete();
        $user->delete();

            //User::destroy($id);
        return response('Deletado com sucesso id ' . $id . '!');
        //}
        //catch (\Throwable $ex)
        //{
        //    return response('Ocorreu um erro ao deletar o usuário com id ' . $id . '!', 500);
        //}
    }
}

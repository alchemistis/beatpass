<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class BasicAuthMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $email = $request->getUser();
        $senha = $request->getPassword();

        $user = User::where('email', $email)
            ->first();

        if ($user === null)
        {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('', 401, $headers);
        }

        if (!password_verify($senha, $user->senha))
        {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('', 401, $headers);
        }

        Auth::setUser($user);

        return $next($request);
    }

}

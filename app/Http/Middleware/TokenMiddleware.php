<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_token = UserToken::where('token','=',$request->header('x-token'))->first();
        $hierarchy = $user_token->user->types()->first()->type()->first()->hierarchy;
        if(isset($user_token)){
            $user = $user_token->user;
            $request->attributes->add([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'token' => $user_token,
                    'type_id' => $user->types->type_id,
                    'type' => $user->types->type,
                    'hierarchy' => $hierarchy
                ]
            ]);

            return $next($request);
        }
        return response()->json([
            "code" => Response::HTTP_UNAUTHORIZED,
            "result" => "Geçersiz token",
        ],Response::HTTP_UNAUTHORIZED);

    }
}

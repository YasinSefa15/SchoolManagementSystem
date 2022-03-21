<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MethodMiddleware
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
        $methods = [
            'create' => 'post',
            'view' => 'get',
            'read' => 'get',
            'update' => 'put',
            'delete' => 'delete',
            'login' => 'post',
            'register' => 'post'
        ];
        $action = Str::after($request->route()->getName(),'.');
        if (isset($methods[$action])
            && $methods[$action] == Str::lower($request->method())){
            return $next($request);
        }
        else{
            return response()->json([
                "code" => Response::HTTP_METHOD_NOT_ALLOWED,
                "message" => "Geçersiz metod.",
            ],Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}

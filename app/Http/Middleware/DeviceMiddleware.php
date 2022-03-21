<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('x-device') == 'mobile'  ||
            $request->header('x-device') == 'desktop' ||
            $request->header('x-device') == 'web'     ||
            $request->header('x-device') == 'mobile'){
            return $next($request);
        }


        return response()->json([
            "code" => Response::HTTP_UNAUTHORIZED,
            "message" => "Cihaz doğrulaması yapın",
        ],Response::HTTP_UNAUTHORIZED);
    }
}

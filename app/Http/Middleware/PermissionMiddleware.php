<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
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
        //işlemi yapan kişinin yetkisi var mı
        $user_type = DB::table('type_to_permissions')->where('type_id','=',$request->get('user')['type_id'])->first();
        //dd(isset($user_type));
        //dd($request->route()->user_id);
        //dd($request->get('user')['id']);
        if ($request->get('user')['type'] == 'admin' || isset($user_type)){
            if (!($request->get('user')['id'] ==$request->route()->user_id ||
                $request->get('user')['id'] ==$request->route()->id)){
                return response()->json([
                    "code" => Response::HTTP_FORBIDDEN,
                    "result" => "Yetkisiz işlem",
                ],Response::HTTP_FORBIDDEN);
            }
            return $next($request);
        }



        return response()->json([
            "code" => Response::HTTP_FORBIDDEN,
            "result" => "Yetkisiz işlem",
        ],Response::HTTP_FORBIDDEN);
    }
}

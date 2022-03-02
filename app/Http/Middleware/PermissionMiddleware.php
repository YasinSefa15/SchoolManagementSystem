<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\TypeToPermission;
use App\Models\UserType;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** todo: türün route erişimi var. ama kendisi dışındaki bilgilerle oynayabiliyor? */
        $userPermission = DB::table('type_to_permissions')
            ->where('type_id','=',$request->get('user')['type_id'])
            ->join('module_to_routes','module_to_routes.id','=','type_to_permissions.module_to_route_id')
            ->where('module_to_routes.route_name','=',$request->route()->getName())
            ->select('type_to_permissions.type')
            ->first();
        if ($userPermission){
            if ($userPermission->type == 'general'){
                return $next($request);
            }
            if (($request->route()->id) && $request->route()->id == $request->get('user')['id']){
                return $next($request);
            }
        }
        return response()->json([
            "code" => Response::HTTP_FORBIDDEN,
            "result" => "Yetkisiz işlem",
        ],Response::HTTP_FORBIDDEN);
    }
}

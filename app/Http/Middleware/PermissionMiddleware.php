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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** todo: türün route erişimi var. ama kendisi dışındaki bilgilerle oynayabiliyor? */
        //işlemi yapan kişinin yetkisi var mı
        $user_type = DB::table('type_to_permissions')
            ->where('type_id','=',$request->get('user')['type_id'])
            ->join('modules_routes','modules_routes.id','=','type_to_permissions.modules_routes_id')
            ->where('modules_routes.route_name','=',$request->route()->getName())
            ->first();

        if ($request->get('user')['type'] == 'admin'){
            return $next($request);
        }
        //!!!!!
        //erişmek istediği kişinin hiyerarşisi kendisininkine eşitse, kendisininde işlem yapabilir
        //ama ben belki lesson a erişmek istiyorum
        //erişmek istediği kişinin hiyerarşisi fazlaysa erişim yok

//        //kendi hierarch sinden bir eskiğinin oraya erişimi var mı
        if ($request->get('user')['hierarchy'] -1 == 0){
            if ($request->get('user')['id']  == $request->route()->parameter('id'))
                return $next($request);
            else
                return response()->json([
                    "code" => Response::HTTP_FORBIDDEN,
                    "result" => "Yetkisiz işlem",
                ],Response::HTTP_FORBIDDEN);
        }
        else
            $hierarchical = UserType::where('hierarchy',$request->get('user')['hierarchy'] - 1)->first();

//bir altını almaya gerek yok. erişmek istediği route kendisinin üst sınırı mı
        dd($hierarchical->id);//bunun oraya yetkisi var mı
//        dd($request->get('user')['hierarchy']);
//        dd($user_type);

        //route.[0 == type ise kendisi değilse hepsi
        //kendisi dışındakilere erişmeyi kapatırsan erişme yetkisi olanlar erişemiyor. Bu kısımda kod-veri tabanı üzerinde mantık
        //sal bir hata var
        if (isset($user_type)){
            //hiyeraşi kontrol. altının o route a erişimi yoksa ya allah güncelle hepsini

            if (($request->get('user')['type_id'] != $user_type->type_id)){
                return response()->json([
                    "code" => Response::HTTP_FORBIDDEN,
                    "result" => "Yetkisiz işlem",
                ],Response::HTTP_FORBIDDEN);
            }
            //dd($request->get('user')['type']);
            //route adından erişilmek istenilen modele erişip
            return $next($request);
        }

        return response()->json([
            "code" => Response::HTTP_FORBIDDEN,
            "result" => "Yetkisiz işlem",
        ],Response::HTTP_FORBIDDEN);
    }
}

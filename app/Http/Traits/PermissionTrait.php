<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait PermissionTrait
{
    //ihtiyaç yok. ama dursun belki lazım olur :P

    public function createPermission( $config){
        if ($config['user']->types[0]['type'] == 'admin'){
            $modules_routes = DB::table('type_to_permissions')->get();
            dd($modules_routes);
        }elseif ($config['user']->types[0]['type'] == 'student'){
            $modules_routes = DB::table('type_to_permissions')->get();
            dd($modules_routes);
            foreach ($modules_routes as $modules_route){
                dd($modules_route->route_name);
            }
            dd($modules_routes);
        }
        dd($config['user']->types[0]['type']);
    }
}

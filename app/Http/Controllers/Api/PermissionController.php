<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\ModuleRoute;
use App\Models\TypeToPermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ResponseTrait;

    /** todo : didnt like it */
    public function read(Request $request){
        $result = null;

        $result['routes'] = ModuleRoute::select('id','route_name','title','type')->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function create(Request $request){
        $result = TypeToPermission::create([
            'type_id' => $request->get('type_id'),
            'module_to_route_id' => $request->get('module_to_route_id'),
            'type' => $request->get('type')
        ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }
    public function update(Request $request){
        $result = TypeToPermission::query()
            ->where('id',$request->get('id'))
            ->update([
                'type' => $request->get('type')
            ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }
    public function delete(Request $request){
        $result = TypeToPermission::query()
            ->where('id',$request->get('id'))
            ->delete();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'delete');
    }
}

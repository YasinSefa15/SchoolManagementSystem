<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Module;
use App\Models\TypeToPermission;
use App\Models\UserType;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ResponseTrait;

    public function read(Request $request){
        $result['types'] = UserType::get();
        $result['data'] = Module::with('routes:id,module_id,route_name,title,type','routes.permissions')
            ->get();
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

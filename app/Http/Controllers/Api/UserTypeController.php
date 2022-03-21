<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends Controller
{
    use APIMessage, ResponseTrait;
    public function create(Request $request){
        $validator = Validator::make($request->all(),['type' => 'required|string|max:32|unique:user_types']);
        if($validator->fails()) {
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
            $result = UserType::create([
                'type' => $request->get('type')
            ]);

            return $this->responseTrait([
                'code' => null,
                'message' => $request->route()->getName(),
                'result' => $result
            ], 'create');
    }

    public function read(Request $request){
        $result = UserType::get();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function delete(Request $request,$id){
        try {
            $result = DB::table('user_types')
                ->where('id','=',$id)
                ->delete();
        }catch (\Exception $e){
        }

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result ?? null
        ], 'update');
    }

}

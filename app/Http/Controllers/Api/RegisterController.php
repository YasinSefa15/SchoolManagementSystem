<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\PermissionTrait;
use App\Http\Traits\TokenTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use APIMessage, TokenTrait;
    public function create(Request $request){
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'type' => 'required|string|exists:user_types,type',
            'identification' => 'required|integer'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }else{
            $result = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make(substr($request->get('identification'),6)),
                'identification' => Hash::make($request->get('identification'))
            ]);
            $type = DB::table('user_types')->where('type',$request->get('type'))->first();
            $result->types()->create([
                'type_id' => $type->id,
                'type' => $type->type
            ]);
            $this->createToken([
                'user' => $result,
                'device' => 'web'
            ]);
            $result->type = $request->get('type');
            return isset($result) ?
                response()->json(
                    $this->APIMessage([
                        'code' => Response::HTTP_CREATED,
                        'message' => $request->route()->getName(),
                        'result' => $result
                    ]),Response::HTTP_CREATED) :
                response()->json(
                    $this->APIMessage([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => $request->route()->getName()
                    ]),Response::HTTP_BAD_REQUEST);
        }
    }
}

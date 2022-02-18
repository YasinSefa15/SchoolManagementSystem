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

class UserController extends Controller
{
    use APIMessage, TokenTrait, PermissionTrait;
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
                'password' => Hash::make(substr($request->get('identification'),-6)), //5 ti
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

    public function read(Request $request){

        $result = User::cursor()->filter(function($user){
            $user->{'type'} = $user->types[0]['type'];
            unset($user->types);
            return true;
        });

        return isset($result) ?
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_OK,
                    'message' => $request->route()->getName(),
                    'result' => $result
                ]),Response::HTTP_OK) :
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $request->route()->getName()
                ]),Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request,$id){
        $rules = [
            'name' => 'nullable|string',
            'email' => 'nullable|unique:users,email|string',
            'password' => 'nullable|string',
            'type' => 'nullable|string|exists:user_types,type'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => 'Lütfen formu doğru bir şekilde doldurunuz',
                'result' => $validator->errors()
            ]));
        }else{
            $result = User::where('id',$id)->first();
            $result == null ? : $result->update([
               'name' => $request->get('name') ?? $result->name,
               'email' => $request->get('email') ?? $result->email,
               'password' => $request->get('password') ? Hash::make($request->get('password')) :  $result->password
            ]);
            if($request->get('type') !== null) {
                $type = DB::table('user_types')->where('type',$request->get('type'))->first();
                $result->types()->update([
                    'type_id' => $type->id,
                    'type' => $type->type
                ]);
            }
        }
        return isset($result) ?
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_OK,
                    'message' => $request->route()->getName()
                ]),Response::HTTP_OK) :
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $request->route()->getName()
                ]),Response::HTTP_BAD_REQUEST);
    }

    public function view(Request $request,$id){
        $result = DB::table('users')
            ->where('id',$id)
            ->join('user_to_type','users.id','=','user_to_type.user_id')
            ->select('users.id','users.name','users.email','users.created_at','users.updated_at','user_to_type.type')
            ->get();
        return $result ?
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_OK,
                    'message' => $request->route()->getName(),
                    'result' => $result
                ]),Response::HTTP_OK) :
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $request->route()->getName()
                ]),Response::HTTP_BAD_REQUEST);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Traits\APIMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use APIMessage;
    public function read(Request $request){
        $result = User::whereEmail($request->get('email'))->first();
        $token = $result == null ? : $result->tokens()->where('device',$request->header('x-device'))->first()['token'];

        return ($result !=null && Hash::check($request->get('password'),$result->password)) ?
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_OK,
                    'message' => $request->route()->getName(),
                    'result' => $result,
                    'token' => $token
                ]),Response::HTTP_OK) :
            response()->json(
                $this->APIMessage([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $request->route()->getName()
                ]),Response::HTTP_BAD_REQUEST);
    }
}

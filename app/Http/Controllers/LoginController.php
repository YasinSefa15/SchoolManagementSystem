<?php

namespace App\Http\Controllers;

use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use APIMessage, ResponseTrait;
    public function read(Request $request){
        $result = User::whereEmail($request->get('email'))->first();
        $token = $result == null ? : $result->tokens()->where('device',$request->header('x-device'))->first()['token'];

        return ($result !=null && Hash::check($request->get('password'),$result->password)) ?
            $this->responseTrait([
                'code' => null,
                'message' => $request->route()->getName(),
                'result' => $result,
                'token' => $token
            ], 'read') :
            $this->responseTrait([
                'code' => null,
                'message' => $request->route()->getName()
            ], 'read');
    }
}

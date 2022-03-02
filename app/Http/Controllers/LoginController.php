<?php

namespace App\Http\Controllers;

use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\TokenTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use TokenTrait, ResponseTrait;
    public function read(Request $request){
        $result = User::where('number',$request->get('number'))->first();
        $available = $result !=null && Hash::check($request->get('password'),$result->password);
        $token = $available == null ? : $this->token([
            'device' => $request->header('x-device'),
            'user_id' => $result->id
        ]);
        $result['type'] = $result->types()->first()->type;
        return ($available) ?
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

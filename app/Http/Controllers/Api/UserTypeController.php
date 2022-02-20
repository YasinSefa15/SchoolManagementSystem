<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserTypeController extends Controller
{
    use APIMessage, ResponseTrait;
    public function create(Request $request){
        $validator = Validator::make($request->all(),['type' => 'required|string']);
        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
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
}

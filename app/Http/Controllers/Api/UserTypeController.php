<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\Department;
use App\Models\StudentToSupervisior;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function response;

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

    public function read(Request $request){
        $result = UserType::get();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function update(Request $request){
        $rules = [
            'type_id' => 'required|integer|exists:user_types,id',
            'type' => 'required|string'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $result = UserType::query()
            ->where('id',$request->get('type_id'));
        $result->first() == null ? : $result->update(['type' => $request->get('type')]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result->first()
        ], 'update');
    }

}

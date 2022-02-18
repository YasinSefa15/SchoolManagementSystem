<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function response;

class ModuleController extends Controller
{
    public function create(Request $request){
        $rules = [
            'title' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]);
        }else{
            $result = Module::create([
                'title' => $request->get('title'),
                'name' => $request->get('name'),
                'description'=>$request->get('description')
            ]);

            return $result ?
                response()->json([
                    'code' => 200,
                    'message' => "Başarılı",
                    'result' => $result
                ],Response::HTTP_OK) :
                response()->json([
                    'code' => 400,
                    'message' => "Başarısız"
                ],Response::HTTP_BAD_REQUEST);

        }
    }
}

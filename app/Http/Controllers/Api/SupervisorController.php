<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SupervisorController extends Controller
{ //LOG TUTULACAK
    use APIMessage;
    public function create(Request $request){
        $rules = [
            'student_id' => 'required|integer|exists:user_to_type,user_id,type,student',
            'lecturer_id' => 'required|integer|exists:user_to_type,user_id,type,lecturer'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }else{
            $result = Supervisor::create([
                'student_id' => $request->get('student_id'),
                'lecturer_id' => $request->get('lecturer_id')
            ]);
            return isset($result) ?
                response()->json(
                    $this->APIMessage([
                        'code' => Response::HTTP_CREATED,
                        'message' => $request->route()->getName(),
                        'result' => $request->get('type')

                    ]),Response::HTTP_CREATED) :
                response()->json(
                    $this->APIMessage([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => $request->route()->getName()
                    ]),Response::HTTP_BAD_REQUEST);
        }
    }

    public function read(Request $request){
        $result = Supervisor::with('lecturer')->get();
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
            'student_id' => 'required|integer|exists:user_to_type,user_id,type,student',
            'lecturer_id' => 'required|integer|exists:user_to_type,user_id,type,lecturer'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => 'Lütfen formu doğru bir şekilde doldurunuz',
                'result' => $validator->errors()
            ]));
        }

        $result = Supervisor::where('lecturer_id',$id)->first();
        $result == null ? : $result->update($request->all());

        return $result ?
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
        $result = Supervisor::with('student')->where('lecturer_id',$id)->get();
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

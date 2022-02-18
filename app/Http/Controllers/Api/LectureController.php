<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Models\Lecture;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class LectureController extends Controller
{
    use APIMessage;

    //hoca atama, ders gün saat atama
    public function create(Request $request){
        $rules = [
            'name' => 'required|string',
            'year' => 'required|integer',
            'semester' => 'required|in:fall,spring',
            'code' => 'required|string',
            'lecturer_id' => 'required|integer|exists:user_to_type,user_id,type,lecturer',
            'credit' => 'required|integer',
            'registered' => 'nullable|integer',
            'quota' => 'required|integer',
            'date' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }else{
            $result = Lecture::create([
                'name' => $request->get('name'),
                'year' => $request->get('year'),
                'semester' => $request->get('semester')
            ]);

            $result->details()->create([
                'code' => $request->get('code'),
                'lecturer_id' => $request->get('lecturer_id'),
                'credit' => $request->get('credit'),
                'quota' => $request->get('quota'),
                'date' => json_encode($request->get('date'))
            ]);

            $result->offered()->create([]);

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
        $validator = Validator::make($request->all(),['year'=> 'required|integer', 'semester' => 'required|in:spring,fall']);
        $result = DB::table('lectures')
            ->where('year','=',$request->get('year'))
            ->where('semester','=',$request->get('semester'))
            ->get();
        if ($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }
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
            'year' => 'nullable|integer',
            'semester' => 'nullable|in:fall,semester'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => 'Lütfen formu doğru bir şekilde doldurunuz',
                'result' => $validator->errors()
            ]));
        }else{
            $result = Lecture::where('id',$id)->first();
            $result == null ? : $result->update([
                'name' => $request->get('name') ?? $result->name,
                'year' => $request->get('year') ?? $result->email,
                'semester' => $request->get('semester') ?? $request->get('password')
            ]);
        }
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

    public function view(Request $request,$id){
        //o dersi alanlar
        //BU KISIM USER_TO_LECTURE TABLOSU YAPILASIYA KADAR ES GEÇİLDİ
        $result = Lecture::with('details')->where('id',$id)->first();
        //$result = DB::table('lectures')->whereId($id)->first();
        dd($result);
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

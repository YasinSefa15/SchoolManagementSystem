<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Models\LectureDetail;
use App\Models\UserToLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserToLectureController extends Controller
{
    //create-delete
    use APIMessage;
    public function create(Request $request){
        $rules = [
            'user_id' => 'required|integer|exists:user_to_type,user_id,type,student',
            'lecture_id' => 'required|integer|exists:lectures,id'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }else{
            $lecture_detail = DB::table('lecture_details')
                ->where('lecture_id','=',$request->get('lecture_id'))
                ->first();
            if ($lecture_detail->registered < $lecture_detail->quota){
                $result = UserToLecture::create([
                    'user_id' => $request->get('user_id'),
                    'lecture_id' => $request->get('lecture_id')
                ]);

                $result->lectureDetails()->update([
                    'registered' => $lecture_detail->registered + 1
                ]);
            }

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

    //ders harf notuyla beraer dönülmeli.
    public function view(Request $request,$user_id){
        $result = DB::table('user_to_lectures')->where('user_id','=',$user_id)->get();
        return isset($result[0]) ?
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

    //dersi onaylama işlemi
    public function update(Request $request){
        $validator = Validator::make($request->all(),['status' => 'required|in:approved,pending,rejected']);
        if ($validator->fails()){
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }
        $result = UserToLecture::where('user_id',$request->get('user_id'))
            ->where('lecture_id',$request->get('lecture_id'))
            ->first()->update(['status' => 'approved']);

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

    public function delete(Request $request){
        $result = LectureDetail::where('lecture_id','=',$request->get('lecture_id'))
            ->first();
        if($result->users()->where('user_id',$request->get('user_id'))->first() !== null){
            $rel = $result->users()->first()->where('user_id',$request->get('user_id'))->first()->delete();
            $result->decrement('registered');
        }
        return isset($rel) ?
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

}

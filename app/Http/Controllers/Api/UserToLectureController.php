<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\LectureDetail;
use App\Models\UserToLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserToLectureController extends Controller
{
    //create-delete
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'user_id' => 'required|integer|exists:user_to_type,user_id,type,student',
            'lecture_id' => 'required|integer|exists:lectures,id'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $lecture = Lecture::where('id','=',$request->get('lecture_id'))->first();
        if ($lecture->registered < $lecture->quota){
            $result = $lecture->usersToLectures()->create([
               'user_id' => $request->get('user_id')
            ]);
            $lecture->increment('registered');
        }
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result,
        ], 'create');
    }

    //ders harf notuyla beraer dönülmeli. //review edilmedi
    public function view(Request $request,$user_id){
        $result = DB::table('user_to_lectures')->where('user_id','=',$user_id)->first();
        $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
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

        $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function delete(Request $request){
        $result = Lecture::where('id','=',$request->get('lecture_id'))->first();
        if($result->usersToLectures()->where('user_id',$request->get('user_id'))->first() !== null){
            $rel = $result->usersToLectures()->first()->where('user_id',$request->get('user_id'))->first()->delete();
            $result->decrement('registered');
        }
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => (!isset($rel) ? null : $rel)
        ], 'delete');
    }

}

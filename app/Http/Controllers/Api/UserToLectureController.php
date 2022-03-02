<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\UserToLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

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
            'result' => $result ?? null,
        ], 'create');
    }

    public function update(Request $request){
        $rules = [
            'lecture_id' => 'required|integer',
            'user_id' => 'required|integer',
            'status' => 'required|in:approved,pending,rejected'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $result = UserToLecture::where('user_id',$request->get('user_id'))
            ->where('lecture_id',$request->get('lecture_id'));

        if (($result->first())){
            if ($request->get('status') == 'rejected'){
                $result->first()->lectures()->first()->decrement('registered');
                $rel = $result->delete();
            }else{
                $rel = $result->first()->update(['status' => 'approved']);
            }
        }


        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => (!isset($rel) ? null : $rel)
        ], 'update');
    }

    public function read(Request $request){
        $rules = [
            'lecturer_id' => 'required|integer'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
            $result = DB::table('student_to_supervisor')
                ->where('student_to_supervisor.lecturer_id','=',$request->get('lecturer_id'))
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'student_to_supervisor.student_id');
                })
                ->join('user_to_lectures', function ($join) {
                    $join->on('user_to_lectures.user_id','=','users.id');
                })
                ->join('offered_lectures', function ($join) {
                    $join ->where('start_at','<',now())
                        ->where('end_at','>',now());
                })
                ->join('lectures', function ($join) {
                    $join->on('lectures.semester','=','offered_lectures.semester')
                        ->on('lectures.year','=','offered_lectures.year')
                        ->on('lectures.id','=','user_to_lectures.lecture_id');
                })
                ->select('users.name as user_name','lectures.name as lecture_name','user_to_lectures.*','lectures.name','lectures.code')
             ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result,
        ], 'create');
    }

}

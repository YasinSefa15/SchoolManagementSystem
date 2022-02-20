<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\OfferedLecture;
use App\Models\User;
use App\Models\UserToLecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class OfferedLectureController extends Controller
{
    use APIMessage, ResponseTrait;

    public function create(Request $request){
        $rules = [
            'start_at' => 'required|date_format:Y-m-d H:i',
            'end_at' => 'required|date_format:Y-m-d H:i',
            'year' => 'required|date_format:Y',
            'semester' => 'required|in:fall,spring',
            'type' => 'required|in:semester,add-drop',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = OfferedLecture::create($request->all());
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }
    public function update(Request $request){
        $rules = [
            'start_at' => 'nullable|date_format:Y-m-d H:i',
            'end_at' => 'nullable|date_format:Y-m-d H:i',
            'year' => 'required|date_format:Y',
            'semester' => 'required|in:fall,spring',
            'type' => 'required|in:semester,add-drop'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        /** todo: utc ile tutmasını sağla-tr saati ile tutuyo */
        $result = OfferedLecture::where('year',$request->get('year'))
            ->where('semester',$request->get('semester'))
            ->where('type',$request->get('type'))
            ->update($request->all());
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }

    /** todo : departman eşleşmesi yapıp görüntüleyecek */
    public function read(Request $request){
        //öğrencinin departmanına göre dersler gelir.
        $result = DB::table('offered_lectures')
            ->where('start_at','<',now())
            ->where('end_at','>',now())
            ->join('user_to_department',function($join) use($request){
                $join->where('user_to_department.user_id','=',$request->get('user_id'));
            })
            ->join('lectures', function ($join) use ($request){
                $join->on('lectures.department_id', '=', 'user_to_department.department_id')
                    ->on('lectures.semester','=','offered_lectures.semester')
                    ->on('lectures.year','=','offered_lectures.year');
            })
            ->join('users','users.id','=','lectures.lecturer_id')
            ->select('lectures.name AS lecture_name','lectures.id','lectures.code','users.name AS lecturer_name','lectures.credit','users.name','lectures.date')
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    //kullanıcının seçtiği dersler
    public function view(Request $request,$id){
        $result = DB::table('user_to_lectures')
            ->where('user_id','=',$id)
            ->join('lectures','user_to_lectures.lecture_id','=','lectures.id')
            ->join('offered_lectures', function ($join) {
                $join->on('offered_lectures.year', '=', 'lectures.year')
                    ->on('offered_lectures.semester','=','lectures.semester')
                    ->where('start_at','<',now())
                    ->where('end_at','>',now());
            })
            ->join('users','users.id','=','lectures.lecturer_id')
            ->select('lectures.name AS lecture_name','lectures.id','lectures.code', 'users.name AS lecturer_name'
                ,'lectures.credit','users.name','lectures.date','user_to_lectures.status')
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }
}

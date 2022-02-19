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
    //Açılan derslerin zamanlarını günceller.
    //dersin vakti geçtiğinde ise hepsini silsin. ARAŞTIR
    public function update(Request $request){
        $rules = [
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i',
            'year' => 'required|date_format:Y',
            'semester' => 'required|in:fall,spring'
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
        $result = DB::table('lectures')
            ->where('year','=',$request->get('year'))
            ->where('semester','=',$request->get('semester'))
            ->join('offered_lectures','lectures.id','=','offered_lectures.lecture_id')
            ->update([
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date')
            ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    /** todo : departman eşleşmesi yapıp görüntüleyecek */
    public function read(Request $request){
        $result = DB::table('offered_lectures')
            ->where('start_date','<',now())
            ->where('end_date','>',now())
            ->join('lecture_details','lecture_details.lecture_id','=','offered_lectures.lecture_id')
            ->join('lectures','lectures.id','=','offered_lectures.lecture_id')
            ->join('users','users.id','=','lecture_details.lecturer_id')
            ->select('offered_lectures.lecture_id','lecture_details.code','lecture_details.credit','users.name','lecture_details.date')
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    //kullanıcının seçtiği dersler
    public function view(Request $request){
        $result = DB::table('user_to_lectures')
            ->where('user_id','=',$request->get('user')['id'])
            ->join('lecture_details','user_to_lectures.lecture_id','=','lecture_details.lecture_id')
            ->join('offered_lectures','offered_lectures.lecture_id','=','lecture_details.lecture_id')
            ->where('start_date','<',now())
            ->where('end_date','>',now())
            ->join('users','users.id','=','lecture_details.lecturer_id')
            ->select('user_to_lectures.user_id','user_to_lectures.lecture_id','user_to_lectures.status',
                'lecture_details.code','lecture_details.code','lecture_details.credit','users.name')
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }
}

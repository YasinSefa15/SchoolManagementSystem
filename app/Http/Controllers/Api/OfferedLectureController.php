<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
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
    use APIMessage;
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
            return response()->json($this->APIMessage([
                'code' => 400,
                'message' => "Lütfen formunuzu kontrol ediniz.",
                'result' => $validator->errors()
            ]),Response::HTTP_BAD_REQUEST);
        }
        //veritabanında tr saati ile tutuyor
        $result = DB::table('lectures')
            ->where('year','=',$request->get('year'))
            ->where('semester','=',$request->get('semester'))
            ->join('offered_lectures','lectures.id','=','offered_lectures.lecture_id')
            ->update([
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date')
            ]);
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

    //burada ileride departman eşleşmesi yapıp görüntüleyecek
    public function read(Request $request){
        $result = DB::table('offered_lectures')
            ->where('start_date','<',now())
            ->where('end_date','>',now())
            ->join('lecture_details','lecture_details.lecture_id','=','offered_lectures.lecture_id')
            ->join('lectures','lectures.id','=','offered_lectures.lecture_id')
            ->join('users','users.id','=','lecture_details.lecturer_id')
            ->select('offered_lectures.lecture_id','lecture_details.code','lecture_details.credit','users.name','lecture_details.date')
            ->get();

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

    //kullanıcının seçtiği dersler
    public function view(Request $request){
        //user_id middleware den gelecek ileride. SONRA BAKK!!!!!!!!
        $result = DB::table('user_to_lectures')
            ->join('offered_lectures','user_to_lectures.lecture_id','=','offered_lectures.lecture_id')
            ->where('start_date','<',now())
            ->where('end_date','>',now())
            ->get();
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\LectureToExam;
use App\Models\UserToGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserToGradeController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'exam_id' => 'required|integer|exists:lecture_to_exams,id',
            'user_id' => 'required|integer|exists:user_to_lectures,user_id',
            'grade' => 'required|integer'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $model = LectureToExam::where('id',$request->get('exam_id'))->first();
        $model->update([
            'average_note' => ($model->average_note *$model->grades()->count() + $request->get('grade')) / ($model->grades()->count()+1)
            ]) ;

        $result = $model->grades()->create([
           'user_id' => $request->get('user_id'),
            'grade' => $request->get('grade')
        ]);


        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }

    public function update(Request $request){
        $rules = [
            'exam_id' => 'required|integer|exists:lecture_to_exams,id',
            'user_id' => 'required|integer|exists:user_to_lectures,user_id',
            'grade' => 'required|integer|min:-127|max:127'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $model = DB::table('user_to_grades')
            ->where('user_id','=',$request->get('user_id'))
            ->where('exam_id','=',$request->get('exam_id'));

        if (!is_null($model->first())) {
            $prev_note = $model->first()->grade;
            $model = $model->update([
                'grade' => $request->get('grade')
            ]);

            $result = LectureToExam::withCount('grades')->where('id',$request->get('exam_id'))->first();
            $result = $result->update([
                'average_note' => round(($result->grades_count * $result->average_note - $prev_note + $request->get('grade'))
                    / ($result->grades_count))
            ]);
        }

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result?? null
        ], 'update');
    }
}

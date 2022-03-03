<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\LectureToExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureToExamController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'type' => 'required|string',
            'percentage' => 'required|integer'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = LectureToExam::create([
            'lecture_id' => $request->get('user')['id'],
            'type' => $request->get('type'),
            'percentage' => $request->get('percentage')
        ]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }

    public function read(Request $request,$lecture_id){
        $result = Lecture::with('exams')->where('id',$lecture_id)->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function view(Request $request,$id){
        $result = Lecture::where('id','=',$id)->with('exams')->get();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'view');
    }

    public function update(Request $request,$lecture_id,$exam_id){
        $rules = [
            'lecture_id' => 'nullable|integer',
            'type' => 'nullable|string',
            'percentage' => 'nullable|integer'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = LectureToExam::where('id',$exam_id)->first();
        $result == null ? : $result->update($request->all());
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }

    public function delete(Request $request,$lecture_id,$exam_id){
        $result = LectureToExam::where('id',$exam_id)->first();
        $result = $result?->delete();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'delete');
    }
}

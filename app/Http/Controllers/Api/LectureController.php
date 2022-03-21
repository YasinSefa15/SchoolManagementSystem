<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'name' => 'required|string',
            'year' => 'required|string',
            'department_id' => 'required|integer|exists:departments,id',
            'semester' => 'required|in:fall,spring',
            'code' => 'required|string',
            'lecturer_id' => 'required|integer|exists:user_to_type,user_id,type,lecturer',
            'credit' => 'required|integer',
            'quota' => 'required|integer',
            'date' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = Lecture::create([
            'name' => $request->get('name'),
            'department_id' => $request->get('department_id'),
            'code' => $request->get('code'),
            'lecturer_id' => $request->get('lecturer_id'),
            'credit' => $request->get('credit'),
            'quota' => $request->get('quota'),
            'date' => $request->get('date'),
            'year' => $request->get('year'),
            'semester' => $request->get('semester'),
        ]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }

    public function read(Request $request){
        $validator = Validator::make($request->all(),['year'=> 'required|string', 'semester' => 'required|in:spring,fall']);

        if ($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = Lecture::query()
            ->where('year','=',$request->get('year'))
            ->where('semester','=',$request->get('semester'))
            ->get();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function update(Request $request,$id){
        $rules = [
            'name' => 'nullable|string',
            'year' => 'nullable|integer',
            'semester' => 'nullable|in:fall,semester',
            'lecturer_id' => 'nullable|integer|exists:user_to_type,user_id,type,lecturer',
            'code' => 'nullable|string',
            'credit' => 'nullable|integer',
            'quota' => 'nullable|integer',
            'date' => 'nullable|array'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = Lecture::where('id',$id)->first();
        $result == null ? : $result->update($request->all());

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }

    public function view(Request $request,$lecture_id){
        $result = Lecture::with('users','exams','users.grades')
            ->where('id',$lecture_id)
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }
}

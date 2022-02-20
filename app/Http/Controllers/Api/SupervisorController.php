<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\Department;
use App\Models\StudentToSupervisior;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SupervisorController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'department_id' => 'required|integer|exists:departments,department_id',
            'lecturer_id' => 'required|integer|exists:user_to_type,user_id,type,lecturer'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = Supervisor::create([
            'student_id' => $request->get('student_id'),
            'lecturer_id' => $request->get('lecturer_id')
        ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }

    //ilgili departmanın danışmanları
    public function read(Request $request){
        $result = Department::with('lecturers')->where('id',$request->get('department_id'))->get(); //
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    /** todo : student_to_supervisior tablo adı yanlış. ..._supervisor olacak */
    public function update(Request $request){
        $rules = [
            'student_id' => 'required|integer|exists:student_to_supervisior,student_id',
            'lecturer_id' => 'required|integer|exists:lecture_to_supervisor,lecturer_id'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $result = StudentToSupervisior::where('student_id',$request->get('student_id'))->first();
        $result == null ? : $result->update(['lecturer_id' => $request->get('lecturer_id')]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }

    public function view(Request $request,$id){
        $result = StudentToSupervisior::with('students')->where('lecturer_id',$id)->get();
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'view');
    }
}

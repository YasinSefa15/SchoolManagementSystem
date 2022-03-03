<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Department;
use App\Models\StudentToSupervisior;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupervisorController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'department_id' => 'required|integer|exists:departments,id',
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
            'department_id' => $request->get('department_id'),
            'lecturer_id' => $request->get('lecturer_id')
        ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }

    public function update(Request $request){
        $rules = [
            'status' => 'required|in:active,passive',
            'lecturer_id' => 'required|integer|exists:lecturer_to_supervisor,lecturer_id',

        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $result = Supervisor::query()
            ->where('lecturer_id',$request->get('lecturer_id'));
        $result->first() == null ? : $result->update(['status' => $request->get('status')]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result->first()
        ], 'update');
    }
    public function read(Request $request){
        $result = Department::with('lecturers')
            ->where('id',$request->get('department_id'))->get();
        foreach ($result[0]->lecturers as $lecturer){
            $lecturer->loadCount('countStudents');
        }
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
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

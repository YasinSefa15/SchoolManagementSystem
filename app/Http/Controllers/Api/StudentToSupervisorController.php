<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\StudentToSupervisior;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentToSupervisorController extends Controller
{
    use ResponseTrait;

    public function update(Request $request){
        $rules = [
            'student_id' => 'required|integer|exists:student_to_supervisor,student_id',
            'lecturer_id' => 'required|integer|exists:lecturer_to_supervisor,lecturer_id,status,active',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }

        $result = StudentToSupervisior::query()
            ->where('student_id',$request->get('student_id'));
        $result->first() == null ? : $result->update(['lecturer_id' => $request->get('lecturer_id')]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result->first()
        ], 'update');
    }
}

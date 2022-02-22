<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\UserToLetterGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserToLetterGradeController extends Controller
{
    use ResponseTrait;
    public function create(Request $request){
        $rules = [
            'letter_grade' => 'required|in:AA,BA,BB,CB,CC,DC,DD,FF,DZ',
            'user_id' => 'required|integer|exists:user_to_lectures,user_id',
            'lecture' => 'required|integer|exists:user_to_lectures,lecture_id'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = UserToLetterGrade::create([
           'user_id' => $request->get('user_id'),
           'lecture' => $request->get('lecture'),
           'letter_grade' => $request->get('letter_grade')
        ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'create');
    }
    public function update(Request $request){
        $rules = [
            'letter_grade' => 'required|in:AA,BA,BB,CB,CC,DC,DD,FF,DZ',
            'user_id' => 'required|integer|exists:user_to_lectures,user_id',
            'lecture' => 'required|integer|exists:user_to_lectures,lecture_id'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
        $result = UserToLetterGrade::where('user_id', $request->get('user_id'))
            ->where('lecture',$request->get('lecture'))
            ->update([
            'letter_grade' => $request->get('letter_grade')
                 ]);
        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'update');
    }
}

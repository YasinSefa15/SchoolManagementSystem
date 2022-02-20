<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\APIMessage;
use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//git push attempt
class LectureController extends Controller
{
    use APIMessage, ResponseTrait;
    /** todo : hawali */
    public function create(Request $request){
        $rules = [
            'name' => 'required|string',
            'year' => 'required|integer',
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
            'year' => $request->get('year'),
            'semester' => $request->get('semester')
        ]);

        $result->details()->create([
            'code' => $request->get('code'),
            'lecturer_id' => $request->get('lecturer_id'),
            'credit' => $request->get('credit'),
            'quota' => $request->get('quota'),
            'date' => json_encode($request->get('date'))
        ]);

        $result->offered()->create([]);

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function read(Request $request){
        $validator = Validator::make($request->all(),['year'=> 'required|integer', 'semester' => 'required|in:spring,fall']);

        $result = Lecture::with('details')
            ->where('year','=',$request->get('year'))
            ->where('semester','=',$request->get('semester'))
            ->get();
        if ($validator->fails()){
            return $this->responseTrait([
                'code' => 400,
                'message' => 'Lütfen formunuzu kontrol ediniz.',
                'result' => $validator->errors()
            ]);
        }
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
            'code' => 'nullable|string',
            'credit' => 'nullable|integer',
            'quota' => 'nullable|integer',
            'date' => 'nullable|integer'
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
        if ($result != null){
            $result->update([
                'name' => $request->get('name') ?? $result->name,
                'year' => $request->get('year') ?? $result->year,
                'semester' => $request->get('semester') ?? $result->semester
            ]);
            $result->details()->first()->update($request->all());
        }

        return $this->responseTrait([
            'code' => null,
            'message' => $request->route()->getName(),
            'result' => $result
        ], 'read');
    }

    public function view(Request $request,$id){
        $result = Lecture::with('users','details')->where('id',$id)->first();

        return $this->responseTrait([
                'code' => null,
                'message' => $request->route()->getName(),
                'result' => $result
                ], 'read');
    }
}

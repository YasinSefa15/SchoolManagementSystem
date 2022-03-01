<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\UserToGrade;
use App\Models\UserToLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    use ResponseTrait;
    //Kullanıcı not görüntüleme sayfası
    //Transkript görüntüleme

    public function read(Request $request,$id){
        $year = $request->get('year');
        $semester = $request->get('semester');
        $deneme = Lecture::with(['lecturer','userToGrade','exams','faculty','department'])
            ->when(($year && $semester), function ($query) use($year,$semester) {
                $query->where([
                    ['year', $year],
                    ['semester',$semester]
                ]);
            })
            ->get();
        /** todo : harf notu eklenmedi. iyileştirme yapılmalı*/
        $deneme = UserToLecture::with(['lectures.lecturer','lectures.userToGrade',
            'lectures.exams','lectures.faculty','lectures.department'])
            ->where('user_id',$id)
            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => "Not görüntüleme işlemi",
            'result' => $deneme
        ], 'read');
    }
}

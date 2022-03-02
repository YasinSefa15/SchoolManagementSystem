<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Lecture;
use App\Models\UserToGrade;
use App\Models\UserToLecture;
use Illuminate\Database\Eloquent\Builder;
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
        $deneme = UserToLecture::with([
            'lectures.lecturer:id,name',
            'lectures.userToGrade',
            'letterGrades',
            'lectures.exams',
            'lectures.faculty',
            'lectures.department'])
            ->whereHas('lectures', function (Builder $query) use($year,$semester) {
                $query->when(($year && $semester), function ($query) use($year,$semester) {
                    $query->where([
                        ['year', $year],
                        ['semester',$semester]
                    ]);
                });
            })
            ->where('user_id',$id)

            ->get();

        return $this->responseTrait([
            'code' => null,
            'message' => "Not görüntüleme işlemi",
            'result' => $deneme
        ], 'read');
    }
}

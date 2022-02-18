<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'year',
        'semester'
    ];

    public function details(){
        return $this->hasOne(LectureDetail::class,'lecture_id','id');
    }

    public function offered(){
        return $this->hasOne(OfferedLecture::class,'lecture_id','id');
    }


}

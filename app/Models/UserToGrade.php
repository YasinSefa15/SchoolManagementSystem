<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToGrade extends Model
{
    use HasFactory;
    protected $primaryKey = null;
    public $timestamps = false;
    protected $table = 'user_to_grades';

    protected $fillable = [
      'user_id',
      'exam_id',
      'grade'
    ];

    public function exam(){
        return $this->hasOne(LectureToExam::class,'id','exam_id');
    }
}

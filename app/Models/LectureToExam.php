<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureToExam extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'lecture_to_exams';

    protected $fillable = [
      'lecture_id',
      'type',
      'percentage',
      'average_note'
    ];

    public function grades(){
        return $this->hasMany(UserToGrade::class,'exam_id','id');
    }
}

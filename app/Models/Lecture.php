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
        'department_id',
        'code',
        'lecturer_id',
        'credit',
        'registered',
        'quota',
        'date',
        'year',
        'semester'
    ];

    public function usersToLectures(){
        return $this->hasMany(UserToLecture::class,'lecture_id','id');
    }
    public function users(){
        return $this->hasManyThrough(User::class,UserToLecture::class,'lecture_id','id','id');
    }

    public function exams(){
        return $this->hasMany(LectureToExam::class,'lecture_id','id');
    }

}

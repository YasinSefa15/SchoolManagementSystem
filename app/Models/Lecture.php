<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lecture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'date' => 'array',
    ];

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

    public function setCodeAttribute($code){
        $this->attributes ['code'] = Str::upper($code);
    }

    public function usersToLectures(){
        return $this->hasMany(UserToLecture::class,'lecture_id','id');
    }
    public function users(){
        return $this->hasManyThrough(User::class,UserToLecture::class,'lecture_id','id','id','user_id');
    }

    public function exams(){
        return $this->hasMany(LectureToExam::class,'lecture_id','id');
    }

    public function userToGrade(){
        return $this->hasManyThrough(UserToGrade::class,LectureToExam::class,'lecture_id','exam_id','id');
    }

    public function faculty(){
        return $this->hasManyThrough(Faculty::class,Department::class,'id','id','department_id');
    }
    public function department(){
        return $this->hasOne(Department::class,'id','department_id');
    }
    public function lecturer(){
        return $this->hasOne(User::class,'id','lecturer_id');
    }


}

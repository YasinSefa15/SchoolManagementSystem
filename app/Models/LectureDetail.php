<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureDetail extends Model
{
    use HasFactory;

    protected $table = 'lecture_details';

    public $timestamps = false;

    protected $fillable = [
        'lecturer_id',
        'code',
        'lecture_id',
        'credit',
        'registered',
        'quota',
        'date'
    ];

    public function lecture(){
        return $this->hasOne(Lecture::class,'id','lecture_id');
    }

    public function users(){
        return $this->hasMany(UserToLecture::class,'lecture_id','lecture_id');
    }
}

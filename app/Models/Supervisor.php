<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'lecturer_id',
        'student_id'
    ];

    protected $hidden = [
      'lecturer_id',
        'student_id'
    ];

    public function student(){
        return $this->hasOne(User::class,'id','student_id');
    }

    public function lecturer(){
        return $this->hasOne(User::class,'id','lecturer_id');
    }
}

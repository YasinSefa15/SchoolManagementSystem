<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    public $timestamps = false;

    protected $fillable = [
        'faculty_id',
        'name'
    ];

    public function lectures(){
        return $this->hasMany(Lecture::class,'department_id','id');
    }

    public function lecturers(){
        return $this->hasManyThrough(User::class,Supervisor::class,'department_id','id','id','lecturer_id');
    }
}

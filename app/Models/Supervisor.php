<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'lecturer_to_supervisor';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'lecturer_id',
        'department_id'
    ];


    public function department(){
        return $this->hasOne(User::class,'id','department_id');
    }

//    public function students(){
//        return $this->hasMany(User::class,'id','department_id');
//    }

    public function lecturer(){
        return $this->hasOne(User::class,'id','lecturer_id');
    }
}

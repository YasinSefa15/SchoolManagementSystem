<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'identification',
        'number'
    ];
    protected $hidden = [
        'password',
        'identification'
    ];

    public function setPasswordAttribute($password){
        $this->attributes ['password'] = bcrypt($password);
    }
    public function setIdentificationAttribute($password){
        $this->attributes ['identification'] = bcrypt($password);
    }

    public function countStudents(){
        return $this->hasMany(StudentToSupervisior::class,'lecturer_id','id');
    }

    public function supervisior(){
        return $this->hasOne(StudentToSupervisior::class,'student_id','id');
    }

    public function userToDepartment(){
        return $this->hasOne(UserToDepartment::class);
    }

    public function types(){
        return $this->hasOne(UserToType::class);
    }

    public function tokens(){
        return $this->hasMany(UserToken::class);
    }

    public function grades(){
        return $this->hasMany(UserToGrade::class);
    }

    public function letter(){
        return $this->hasMany(UserToLetterGrade::class,'user_id','id');
    }

}

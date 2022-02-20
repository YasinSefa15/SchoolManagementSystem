<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'email_verified_at',
        'remember_token',
        'identification'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

}

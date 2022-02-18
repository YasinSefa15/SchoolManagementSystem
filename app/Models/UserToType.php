<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToType extends Model
{
    use HasFactory;

    protected $table = 'user_to_type';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type_id',
        'type'
    ];

//    protected $hidden = [
//      'user_id',
//      'type_id'
//    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function type(){
        return $this->hasOne(UserType::class,'id','type_id');
    }
}

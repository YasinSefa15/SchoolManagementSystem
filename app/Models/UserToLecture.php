<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToLecture extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $table = 'user_to_lectures';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'lecture_id',
        'status'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

}

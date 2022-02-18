<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferedLecture extends Model
{
    use HasFactory;

    protected $table = 'offered_lectures';

    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'lecture_id',
        'start_date',
        'end_date'
    ];


    public function lecture(){
        return $this->hasOne(Lecture::class,'id','lecture_id');
    }

    //denenmedi
    public function userToLecture(){
        return $this->hasMany(UserToLecture::class);
    }

}

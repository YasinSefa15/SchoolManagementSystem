<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentToSupervisior extends Model
{
    use HasFactory;
    protected $primaryKey = null;
    public $timestamps = false;
    protected $table = 'student_to_supervisior';
    protected $fillable = [
        'lecturer_id',
        'student_id'
    ];

    public function students(){
        return $this->hasMany(User::class,'id','student_id');
    }
}

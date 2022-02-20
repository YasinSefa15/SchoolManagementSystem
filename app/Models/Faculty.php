<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculties';

    public $timestamps = false;

    protected $fillable = [
      'name'
    ];

    public function departments(){
        return $this->hasMany(Department::class,'faculty_id','id');
    }
}

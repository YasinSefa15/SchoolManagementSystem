<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToDepartment extends Model
{
    use HasFactory;

    protected $table = 'user_to_department';
    public $timestamps = false;
    protected $fillable = [
      'user_id',
      'department_id'
    ];
}

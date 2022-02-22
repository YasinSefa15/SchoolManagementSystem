<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToLetterGrade extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'user_to_letter_grade';

    protected $fillable = [
      'user_id',
      'lecture',
      'letter_grade'
    ];
}

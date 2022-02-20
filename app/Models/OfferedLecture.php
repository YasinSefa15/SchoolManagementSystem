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
        'year',
        'semester',
        'type',
        'start_at',
        'end_at'
    ];

    public function lectures(){
        return $this->hasMany(Lecture::class,'year','year');
    }


}

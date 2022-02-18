<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'user_types';
    public $timestamps = false;
    protected $fillable = [
        'type'
    ];

    public function types(){
        return $this->hasMany(UserToType::class);
    }
}

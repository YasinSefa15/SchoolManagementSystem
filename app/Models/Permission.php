<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;
    //Log işlemi
    protected $fillable = [
        'user_id',
        'route_name'
    ];

    public function route(){
        return $this->hasOne(ModuleRoute::class,'id','module_route_id');
    }
}

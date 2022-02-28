<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeToPermission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type_id',
        'module_to_route_id',
        'type'
    ];

    public function route(){
        return $this->hasOne(ModuleRoute::class,'id','modules_routes_id');
    }

    public function type(){
        return $this->hasOne(UserType::class,'id','type_id');
    }
}

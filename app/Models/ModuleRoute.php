<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleRoute extends Model
{
    use HasFactory;

    protected $table = 'module_to_routes';

    protected $fillable = [
        'module_id',
        'route_name',
        'title',
        'type'
    ];

    public function module(){
        return $this->hasOne(Module::class);
    }

    public function permissions(){
        return $this->hasMany(TypeToPermission::class,'module_to_route_id','id');
    }
}

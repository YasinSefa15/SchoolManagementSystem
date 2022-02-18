<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleRoute extends Model
{
    use HasFactory;

    protected $table = 'modules_routes';

    protected $fillable = [
        'module_id',
        'route_name',
        'title',
        'type'
    ];

    public function module(){
        return $this->hasOne(Module::class);
    }
}

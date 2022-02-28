<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps =  false;
    protected $fillable = [
      'title',
      'name',
      'description'
    ];

    public function routes(){
        return $this->hasMany(ModuleRoute::class);
    }
}

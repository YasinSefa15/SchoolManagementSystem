<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait TokenTrait
{
    use PermissionTrait;
    public function createToken( $config){ //config-user,device
       return $config['user']->tokens()->create([
          'token' => Str::random(255),
           'device' => $config['device']
       ])->token;
    }

    public function newToken(){
        //userToken ı günceller.
        return "asd";
    }
}

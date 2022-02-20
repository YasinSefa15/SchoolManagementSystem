<?php

namespace App\Http\Traits;

use App\Models\UserToken;
use Illuminate\Support\Str;

trait TokenTrait
{
    public function token($device,$user_id){
        $token = UserToken::firstOrCreate(
            ['user_id' => $user_id,],
            ['token' => Str::random(255),
            'device' => $device
        ]);
        return $token['token'];
    }
}

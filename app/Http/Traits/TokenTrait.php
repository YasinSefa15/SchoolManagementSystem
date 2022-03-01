<?php

namespace App\Http\Traits;

use App\Models\UserToken;
use Illuminate\Support\Str;

trait TokenTrait
{
    public function token($device,$user_id){
        $token = UserToken::firstOrCreate(
            ['user_id' => $user_id,
            'device' => $device],
            ['token' => Str::random(255)]
        );
        return $token['token'];
    }
}

<?php

namespace App\Http\Traits;

use App\Models\UserToken;
use Illuminate\Support\Str;

trait TokenTrait
{
    public function token(array $config){
        $newToken = Str::random(255);
        $userToken = UserToken::where(
            ['user_id' => $config['user_id'],
            'device' => $config['device']])
            ->update([
                'token' => $newToken,
                'last_used_at' => now()
            ]);
        $userToken ? : UserToken::create([
            'user_id' => $config['user_id'],
            'token' => $newToken,
            'device' => $config['device'],
            'last_used_at' => now()
        ]);
        return $newToken;
    }
}

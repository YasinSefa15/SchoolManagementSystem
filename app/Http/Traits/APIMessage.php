<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait APIMessage
{
    public function APIMessage(array $config,string $type = null){
        $message =  $this->codes([
            'code' => $config['code'],
            'message' => $config['message'],
            'type' => $type
        ]);
        return $message;
    }

    private function codes(array $config){
        $title = DB::table('module_to_routes')->where('route_name',$config['message'])->first();
        if($title){
            $message = $title->title . " ".( $config['code'] != 400 ? "işlemi başarılı" : "işlemi başarısız");
        }
        elseif(!is_null($config['type'])){
            $message = $config['message'] .( $config['code'] != 400 ? " başarılı" : " başarısız");
        }
        return $message ?? $config['message'];
    }

}

<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait APIMessage
{
    public function APIMessage(array $config,array $request = null){
        $result =  [
            'code' => $config['code'],
            'message' => $this->codes([
                'code' => $config['code'],
                'message' => $config['message']
            ])
        ];

        if(isset($config['result'])){
            $result = array_merge($result,[
                'result' => $config['result']
            ]);
        }
        if (isset($config['token'])){
            $result = array_merge($result,[
               'token' => $config['token']
            ]);
        }
        return $result;
    }

    private function codes(array $config){
        $title = DB::table('modules_routes')->where('route_name',$config['message'])->first();
        if($title){
            $message = $title->title . " ".( $config['code'] != 400 ? "işlemi başarılı" : "işlemi başarısız");
        }
        return $message ?? $config['message'];
    }

}

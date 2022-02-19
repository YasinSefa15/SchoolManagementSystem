<?php

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    use APIMessage;
    public function responseTrait(array $config, string $type = null){
        $config = $this->getCode($config,$type);
        $config['message'] = $this->APIMessage([
            'code' => $config['code'],
            'message' => $config['message']
        ]);
        return response()->json([$config],$config['code']);
    }

    //cannot return 204x
    public function getCode($config,$type = null){
        $config['code'] = 400;
        if (!isset($config['result']) || $config['result'] == null){
            unset($config['result']);
            $config['code'] = Response::HTTP_BAD_REQUEST;
            return $config;
        }
        if ($type == 'read' || $type == 'view' || $type == 'update'){
            $config['code'] = Response::HTTP_OK;
        }elseif ($type == 'create'){
            $config['code'] = Response::HTTP_CREATED;
        }else{
            $config['code'] = Response::HTTP_BAD_REQUEST;
        }
        return $config;
    }

}

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
        ],$type);
        return response()->json($config,$config['code']);
    }

    public function getCode($config,$type = null){
        if (!isset($config['result']) || $config['result'] == null){
            unset($config['result']);
            $config['code'] = Response::HTTP_BAD_REQUEST;
            return $config;
        }
        if ($type == 'read' || $type == 'view' || $type == 'update' || $type == 'delete'){
            $config['code'] = Response::HTTP_OK;
        }elseif ($type == 'create'){
            $config['code'] = Response::HTTP_CREATED;
        }else{
            $config['code'] = Response::HTTP_BAD_REQUEST;
        }
        return $config;
    }

}

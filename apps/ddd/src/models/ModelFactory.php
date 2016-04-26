<?php

namespace ddd\models;

use ProxyManager\Factory\AccessInterceptorScopeLocalizerFactory as Factory;

class ModelFactory{

    private $persistence;

    public function __construct($persistence){
        $this->persistence = $persistence;
    }

    public function build($modelClass, $data = NULL){
        if($this->persistence == 'maestro'){
            $proxyClass = str_replace('models', 'persistence\\maestro\\proxy', $modelClass) . 'Proxy';
            return new $proxyClass($data);
        }
    }
}
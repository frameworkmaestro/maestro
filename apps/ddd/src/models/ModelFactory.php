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
            $function = new \ReflectionClass($modelClass);
            $model = $function->getShortName();
            $proxyClass = str_replace('models', "persistence\\maestro\\{$model}", $modelClass) . 'Proxy';
            mdump('proxyClass = ' . $proxyClass);
            return new $proxyClass($data);
        }
    }
}
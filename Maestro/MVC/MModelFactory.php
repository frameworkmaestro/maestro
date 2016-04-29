<?php

namespace Maestro\MVC;

class MModelFactory{

    private $persistence;

    public function __construct($persistence){
        $this->persistence = $persistence;
    }

    public function build($modelClass, $data = NULL){
        if($this->persistence == 'maestro'){
            $function = new \ReflectionClass($modelClass);
            $model = strtolower($function->getShortName());
            $proxyClass = str_replace('models', "persistence\\maestro\\{$model}", $modelClass);
            mdump('proxyClass = ' . $proxyClass);
            mdump($data);
            $proxy = new $proxyClass();
            $proxy->getMap()->onCreate($data);
            return $proxy;
        }
    }
}
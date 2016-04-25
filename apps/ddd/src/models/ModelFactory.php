<?php

namespace ddd\models;

class ModelFactory{

    private $persistence;

    public function __construct($persistence){
        $this->persistence = $persistence;
    }

    public function build($modelClass, $data = NULL){
        if($this->persistence == 'maestro'){
            $modelClass = str_replace('models', 'models\\map', $modelClass) . 'Map';
            return new $modelClass($data);
        }
    }
}
<?php
namespace Maestro\MVC;

class MModelFactory
{

    private $persistence;

    public function __construct($persistence)
    {
        $this->persistence = $persistence;
    }

    public function build ($modelClass, $data = NULL) {
        $function = new \ReflectionClass($modelClass);
        $modelName = strtolower($function->getShortName());
        $proxyClassName = str_replace('models', "persistence\\maestro\\{$modelName}", $modelClass);
        //$model = new $modelClass;
        mdump('proxyClassName = ' . $proxyClassName);
        //$proxy = new $proxyClassName($data, $model);
        $proxy = new $proxyClassName();
        $proxy->getMap()->onCreate($data);
        return $proxy;
    }



}
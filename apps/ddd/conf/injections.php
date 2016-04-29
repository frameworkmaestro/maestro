<?php

use \Maestro\Manager;

return [
    'ddd\models\repository\\*RepositoryInterface' => function (\DI\Container $c, \DI\Factory\RequestedEntry $entry) {
        $persistence = Manager::getOptions('persistence');
        if ($persistence == 'maestro') {
            $name = $entry->getName();
            $reflection = new ReflectionClass($name);
            $shortName = $reflection->getShortName();
            $model = str_replace("ReadRepositoryInterface", '', str_replace("WriteRepositoryInterface", '', $shortName));
            $class = "ddd\\persistence\\maestro\\" . $model . "\\" . str_replace("Interface", '', $shortName);
            return new $class();
        } else {
            //return new $class;
        }
    },
    'ddd\services\\*' => function (\DI\Container $c, \DI\Factory\RequestedEntry $entry) {
        $class = $entry->getName();
        $reflection = new ReflectionClass($class);
        $params = $reflection->getConstructor()->getParameters();
        $constructor = array();
        foreach ($params as $param) {
            $constructor[] = $c->get($param->getClass()->getName());
        }
        return new $class(...$constructor);
    },
    'ddd\controllers\\*' => function (\DI\Container $c, \DI\Factory\RequestedEntry $entry) {
        $class = $entry->getName();
        mdump('*'.$class);
        $controller = new $class(MApp::getContext());
        $reflection = new ReflectionClass($class);
        //$params = $reflection->getConstructor()->getParameters();
        $params = $reflection->getMethod('services')->getParameters();
        $services = array();
        foreach ($params as $param) {
            $services[] = $c->get($param->getClass()->getName());
        }
        $controller->services(... $services);
        //return new $class(...$constructor);
        return $controller;
    }
];
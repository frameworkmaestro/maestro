<?php

use \Maestro\Manager;

return [
    /*
    Doctrine\ORM\EntityManager::class => function (DI\Container $c) {
        $activeConf = $c->get('config.database.active');
        $conf = $c->get("config.database.{$activeConf}");
        $modelsNS = $c->get("static.namespace.models");
        $factory = new Kancolle\Infrastructure\Persistence\Doctrine\EntityManagerFactory();
        return $factory->build($conf, $modelsNS);
    },
    SpecificationFactory::class => DI\object(SpecificationFactory::class)->constructor(\DI\get('config.persistence')),
    */
    \ddd\models\ModelFactory::class => function () {
        $persistence = Manager::getOptions('persistence');
        return new \ddd\models\ModelFactory($persistence);
    },
    'ddd\models\repository\\*RepositoryInterface' => function (\DI\Container $c, \DI\Factory\RequestedEntry $entry) {
        $persistence = Manager::getOptions('persistence');
        if ($persistence == 'maestro') {
            $name = $entry->getName();
            $reflection = new ReflectionClass($name);
            $shortName = $reflection->getShortName();
            $model = str_replace("ReadRepositoryInterface", '', str_replace("WriteRepositoryInterface", '', $shortName));
            $class = "ddd\\persistence\\maestro\\" . $model . "\\" . str_replace("Interface", '', $shortName);
            $reflection = new ReflectionClass($class);
            $params = $reflection->getConstructor()->getParameters();
            $constructor = array();
            foreach ($params as $param) {
                $constructor[] = $c->get($param->getClass()->getName());
            }
            return new $class(...$constructor);
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
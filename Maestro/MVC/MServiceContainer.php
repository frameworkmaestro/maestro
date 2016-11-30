<?php
namespace Maestro\MVC;

use Maestro\Manager;

class MServiceContainer
{
    protected $services;
    protected $readRepository;
    protected $writeRepository;

    public function __call($name, $parameters) {
        return $this->services[$name]->execute($parameters[0], $parameters[1], $parameters[2], $parameters[3]);
    }

    public function __get($name) {
        return $this->$name;
    }

    public function add($name, $object){
        $this->services[$name] = $object;
    }

    public function addReadRepository($object){
        $this->readRepository = $object;
    }

    public function addWriteRepository($object){
        $this->writeRepository = $object;
    }
}

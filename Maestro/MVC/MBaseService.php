<?php
namespace Maestro\MVC;
use Maestro\Manager;

class MBaseService extends MService
{
    protected $modelFactory;

    public function __construct()
    {
        $persistence = Manager::getOptions('persistence');
        $this->modelFactory = new MModelFactory($persistence);

    }

    public function getModel($className, $data = null) {
        mdump('getmodel = '.$className);
        mdump($data);
        return $this->modelFactory->build($className, $data);
    }

    public function getMap($className, $data = null) {
        return $this->modelFactory->build($className, $data)->getMap();
    }

}

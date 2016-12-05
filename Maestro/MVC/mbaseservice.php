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

    public function __invoke($parameters)
    {
        $this->execute($parameters);
    }

    public function getModel($className, $data = null)
    {
        return $this->modelFactory->build($className, $data);
    }

    //public function getMap($className, $data = null) {
    //    return $this->modelFactory->build($className, $data)->getMap();
    //}

    public function run($parameters)
    {

    }

    public function execute($parameters)
    {
        return $this->run($parameters);
    }

}

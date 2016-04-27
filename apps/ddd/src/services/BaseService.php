<?php

namespace ddd\services;

class BaseService extends \MService
{
    protected $modelFactory;

    public function __construct(\ddd\models\ModelFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    public function getModel($className, $data = null) {
        return $this->modelFactory->build($className, $data);
    }

    public function getMap($className) {
        return $this->modelFactory->build($className)->getMap();
    }

}

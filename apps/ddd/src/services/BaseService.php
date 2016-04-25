<?php

namespace ddd\services;

class BaseService extends \MService
{
    protected $modelFactory;

    public function __construct(\ddd\models\ModelFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

}

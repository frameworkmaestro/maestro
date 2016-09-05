<?php
/**
 *
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */
namespace Maestro\MVC;

class MBaseRepository
{
    protected $modelFactory;

    public function __construct()
    {
        $persistence = Manager::getOptions('persistence');
        $this->modelFactory = new MModelFactory($persistence);
    }

    public function getModel($className, $data = null) {
        return $this->modelFactory->build($className, $data);
    }

    //public function getMap($className, $data = null) {
    //    return $this->modelFactory->build($className, $data);
    //}

    public function getById($className, $id) {
        $model = $this->modelFactory->build($className);
        mdump('getbyid = ' . $id);
        $model->getMap()->getById($id);
        mdump('ispersistent ? ' . ($model->getMap()->ispersistent() ? 'true' : 'false'));
        return $model;
    }

}

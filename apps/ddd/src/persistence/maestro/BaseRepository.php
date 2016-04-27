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

namespace ddd\persistence\maestro;
use Maestro\Manager;

abstract class BaseRepository
{
    protected $modelFactory;

    public function __construct()
    {
        $this->modelFactory = \Mapp::getContainer()->get(\ddd\models\ModelFactory::class);
    }

    public function getModel($className) {
        return $this->modelFactory->build($className);
    }

    public function getMap($className) {
        return $this->modelFactory->build($className)->getMap();
    }

}

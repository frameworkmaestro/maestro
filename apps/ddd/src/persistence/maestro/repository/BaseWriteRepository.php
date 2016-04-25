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

namespace ddd\persistence\maestro\repository;

use Maestro\Persistence\PersistentManager;

abstract class BaseWriteRepository
{
    protected $pm;

    public function __construct()
    {
        $this->pm = PersistentManager::getInstance();
    }

}

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

use ddd\models\repository\PersonWriteRepositoryInterface;

class PersonWriteRepository extends BaseWriteRepository implements PersonWriteRepositoryInterface
{

    public function save($person) {
        try {
            $this->pm->saveObject($person);
        } catch (\Exception $e) {
            throw new \Exception('Error saving Person.');
        }
    }
    

}


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

use \ddd\models\repository\PersonReadRepositoryInterface;

class PersonReadRepository extends BaseReadRepository implements PersonReadRepositoryInterface
{

    public function listByFilter($person, $filter) {
        $criteria = $this->pm->getRetrieveCriteria($person)
            ->select('*')
            ->orderBy('name');
        if ($filter->name) {
            $criteria->where("name like '{$filter->name}%'");
        }
        return $criteria;
    }

    public function getDescription(){
        return $this->getId();
    }
}

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

namespace ddd\persistence\maestro\Person;


use \ddd\models\repository\PersonReadRepositoryInterface;
use Maestro\MVC\MBaseRepository;

class PersonReadRepository extends MBaseRepository implements PersonReadRepositoryInterface
{

    public function listByFilter($person, $filter) {
        $criteria = $person->getMap()->getCriteria()
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

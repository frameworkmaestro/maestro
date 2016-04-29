<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace auth\models;

class Person extends map\PersonMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'email' => array('notnull'),
                'nick' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getName();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idPerson');
        if ($filter->idPerson){
            $criteria->where("idPerson = {$filter->idPerson}");
        }
        return $criteria;
    }

    public function listForLookup(){
        $criteria = $this->getCriteria()->select('idPerson, name')->orderBy('name');
        return $criteria;
    }
    
}

?>
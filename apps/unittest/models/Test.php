<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage unittest
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace unittest\models;

class Test extends map\TestMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'idMethod' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdTest();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idTest');
        if ($filter->idTest){
            $criteria->where("idTest LIKE '{$filter->idTest}%'");
        }
        return $criteria;
    }
}

?>
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

class Parameter extends map\ParameterMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'idTest' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdParameter();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idParameter');
        if ($filter->idParameter){
            $criteria->where("idParameter LIKE '{$filter->idParameter}%'");
        }
        return $criteria;
    }
}

?>
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

class Method extends map\MethodMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'idModel' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdMethod();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idMethod');
        if ($filter->idMethod){
            $criteria->where("idMethod LIKE '{$filter->idMethod}%'");
        }
        return $criteria;
    }
}

?>
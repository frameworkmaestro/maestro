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

class Model extends map\ModelMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'idApp' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdModel();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idModel');
        if ($filter->idModel){
            $criteria->where("idModel LIKE '{$filter->idModel}%'");
        }
        return $criteria;
    }
}

?>
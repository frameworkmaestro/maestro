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

namespace fnbr20\models;

class Property extends map\PropertyMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'idEntity' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdProperty();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idProperty');
        if ($filter->idProperty){
            $criteria->where("idProperty LIKE '{$filter->idProperty}%'");
        }
        return $criteria;
    }
}

?>
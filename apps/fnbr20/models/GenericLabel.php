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

class GenericLabel extends map\GenericLabelMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'entry' => array('notnull'),
                'idEntity' => array('notnull'),
                'idColor' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdGenericLabel();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idGenericLabel');
        if ($filter->idGenericLabel){
            $criteria->where("idGenericLabel LIKE '{$filter->idGenericLabel}%'");
        }
        return $criteria;
    }
    
    public function getTargetIdEntity() {
        $criteria = $this->getCriteria()->select('*')->orderBy('idGenericLabel');
        if ($filter->idGenericLabel){
            $criteria->where("idGenericLabel LIKE '{$filter->idGenericLabel}%'");
        }
        return $criteria;
    }
}

?>
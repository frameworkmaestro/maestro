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

class Access extends map\AccessMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'rights' => array('notnull'),
                'idGroup' => array('notnull'),
                'idTransaction' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdAccess();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idAccess');
        if ($filter->idAccess){
            $criteria->where("idAccess LIKE '{$filter->idAccess}%'");
        }
        return $criteria;
    }
}

?>
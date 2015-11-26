<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage vendas0
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace auth\models;

class Log extends map\LogMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'idUsuario' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdLog();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLog');
        if ($filter->idLog){
            $criteria->where("idLog LIKE '{$filter->idLog}%'");
        }
        return $criteria;
    }
}

?>
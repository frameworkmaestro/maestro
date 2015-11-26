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

class App extends map\AppMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdApp();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idApp');
        if ($filter->idApp){
            $criteria->where("idApp LIKE '{$filter->idApp}%'");
        }
        return $criteria;
    }
}

?>
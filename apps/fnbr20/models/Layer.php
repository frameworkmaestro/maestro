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

class Layer extends map\LayerMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'rank' => array('notnull'),
                'timeline' => array('notnull'),
                'idAnnotationSet' => array('notnull'),
                'idLayerType' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdLayer();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayer');
        if ($filter->idLayer){
            $criteria->where("idLayer LIKE '{$filter->idLayer}%'");
        }
        return $criteria;
    }


}


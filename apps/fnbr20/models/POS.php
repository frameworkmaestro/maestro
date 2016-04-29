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

class POS extends map\POSMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'POS' => array('notnull'),
                'entry' => array('notnull'),
                'timeline' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdPOS();
    }

    public function listAll(){
        $criteria = $this->getCriteria()->select('idPOS, POS, entry, idEntity')->orderBy('idPOS');
        return $criteria;
    }
    
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idPOS');
        if ($filter->idPOS){
            $criteria->where("idPOS LIKE '{$filter->idPOS}%'");
        }
        return $criteria;
    }
    
    public function listForCombo(){
        $criteria = $this->getCriteria()->select('idPOS, entry.name as name')->orderBy('entry.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }    
}

?>
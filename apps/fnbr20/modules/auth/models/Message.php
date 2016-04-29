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

class Message extends map\MessageMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'idUser' => array('notnull'),
                'idMsgStatus' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdMessage();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idMessage');
        if ($filter->idMessage){
            $criteria->where("idMessage LIKE '{$filter->idMessage}%'");
        }
        return $criteria;
    }
}

?>
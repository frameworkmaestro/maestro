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

class MessageBox extends map\MessageBoxMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'idUser' => array('notnull'),
                'idMsgStatus' => array('notnull'),
                'idMessage' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdMessageBox();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idMessageBox');
        if ($filter->idMessageBox){
            $criteria->where("idMessageBox LIKE '{$filter->idMessageBox}%'");
        }
        return $criteria;
    }
}

?>
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

class ViewLU extends map\ViewLUMap {

    public static function config()
    {
        return [];
    }

    public function listByFrame($idFrame, $idLanguage = '', $idLU = NULL)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('name');
        $criteria->where("idFrame = {$idFrame}");
        $criteria->where("idLanguage = {$idLanguage}");
        if ($idLU) {
            if (is_array($idLU)) {
                $criteria->where("idLU", "IN", $idLU);
            } else {
                $criteria->where("idLU = {$idLU}");
            }
        }
        $criteria->orderBy('name');
        return $criteria;
    }

}


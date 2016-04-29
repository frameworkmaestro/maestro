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

class Language extends map\LanguageMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'language' => array('notnull'),
                'description' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLanguage');
        if ($filter->idLanguage){
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        if ($filter->language){
            $criteria->where("language LIKE '{$filter->language}%'");
        }
        return $criteria;
    }
    
    public function listForCombo(){
        $criteria = $this->getCriteria()->select('idLanguage, language')->orderBy('language');
        return $criteria;
    }

    public function getByLanguage($language){
        $criteria = $this->getCriteria()->select('*')->where("language = '{$language}'");
        $this->retrieveFromCriteria();
        
    }
    
}

?>
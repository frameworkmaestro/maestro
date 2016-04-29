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

class ViewAnnotationSet extends map\ViewAnnotationSetMap {

    public static function config()
    {
        return [];
    }

    public function listBySubCorpus($idSubCorpus, $sortable = NULL) {
        $criteria = $this->getCriteria()->
        select('idAnnotationSet, idSentence, sentence.text, entries.name as annotationStatus, idAnnotationStatus, annotationstatustype.color.rgbBg')->
        where("idSubCorpus = {$idSubCorpus}");
        if ($sortable) {
            if ($sortable->field == 'status') {
                $criteria->orderBy('entries.name ' . $sortable->order);
            }
        }
        Base::entryLanguage($criteria);
        return $criteria;
    }


    public function listFECEBySubCorpus($idSubCorpus) {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
        SELECT *
        FROM view_labelfecetarget
        WHERE (idSubCorpus = {$idSubCorpus})
            AND (idLanguage = {$idLanguage} )
        ORDER BY idSentence,startChar

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('idSentence', 'startChar,endChar,rgbFg,rgbBg,instantiationType');
        return $result;
    }

}


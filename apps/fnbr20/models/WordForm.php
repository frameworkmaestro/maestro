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

class WordForm extends map\WordFormMap
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(
                'form' => array('notnull'),
                'timeline' => array('notnull'),
                'idLexeme' => array('notnull'),
                'idLanguage' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription()
    {
        return $this->getIdWordForm();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idWordForm');
        if ($filter->idWordForm) {
            $criteria->where("idWordForm LIKE '{$filter->idWordForm}%'");
        }
        return $criteria;
    }

    public function listLUByWordForm($wordform)
    {
        $criteria = $this->getCriteria();
        $criteria->select('lexeme.lexemeentries.lemma.lus.idLU');
        $criteria->where("upper(form) = upper('{$wordform}')");
        $lus = $criteria->asQuery()->chunkResult('idLU', 'idLU');
        if (count($lus) > 0) {
            $lu = new LU();
            $criteria = $lu->getCriteria()->select("idLU, concat(frame.entries.name,'.',name) as fullName");
            Base::relation($criteria, 'lu', 'frame', 'rel_evokes');
            Base::entryLanguage($criteria, 'frame');
            $criteria->where("idLU", "IN", $lus);
            $criteria->where("lemma.idLanguage", "=", "frame.entries.idLanguage");
            return $criteria->asQuery()->chunkResult('idLU', 'fullName');
        } else {
            return new \stdClass();
        }
    }

    public function lookFor($words) {
        $criteria = $this->getCriteria()->select('form as i, form');
        $criteria->where("form", "in", $words);
        return $criteria->asQuery()->chunkResult('i','form');
    }

}


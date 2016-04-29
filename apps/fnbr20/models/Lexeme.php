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

class Lexeme extends map\LexemeMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'name' => array('notnull'),
                'timeline' => array('notnull'),
                'idPOS' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getName();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLexeme');
        if ($filter->idLexeme){
            $criteria->where("idLexeme LIKE '{$filter->idLexeme}%'");
        }
        return $criteria;
    }
    
    public function listForGridLemma($filter){
        $criteria = $this->getCriteria()->select("idLexeme, concat(name,'.',pos.POS, '  [',language.language, ']') as fullname")->orderBy('name');
        if ($filter->lexeme){
            $criteria->where("upper(name) = upper('{$filter->lexeme}')");
        }
        if ($filter->language){
            $criteria->where("language.language = '{$filter->language}'");
        }
        return $criteria;
    }
    
    public function save($data) {
        try {
            $transaction = $this->beginTransaction();
            $this->setData($data);
            parent::save();
            $wordform = new Wordform();
            $wordform->setIdLexeme($this->getId());
            foreach($data->listWordform as $wf) {
                $wordform->setPersistent(false);
                $wordform->setForm($wf->wordform);
                //$wordform->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Upload de lexeme+wordform em texto simples (wordform POS lexeme)
     * Parâmetro data informa: idLanguage
     * @param type $data
     * @param type $file 
     */
    public function uploadLexemeWordform($data, $file) {
        $idLanguage = $data->idLanguage;
        $pos = new POS();
        $POS = $pos->listAll()->asQuery()->chunkResult('POS','idPOS');
        $wf = new Wordform();
        $transaction = $this->beginTransaction();
        try {
            $lineNum = 0;
            $rows = file($file->getTmpName());
            foreach ($rows as $row) {
                $lineNum++;
                $row = trim($row);
                if (($row == '') || (substr($row, 0, 2) == "//")) {
                    continue;
                }
                $fields = explode(' ', $row);
                $idPOS = $POS[$fields[1]];
                if ($idPOS != '') {
                    $l = str_replace("'","\'", $fields[2]);
                    $lexeme = $this->getCriteria()->select('idLexeme')
                            ->where("(name = '{$l}') and (idPOS = {$idPOS}) and (idLanguage = {$idLanguage})")->asQuery()->getResult();
                    $idLexeme = $lexeme[0]['idLexeme'];
                    if ($idLexeme == '') {
                        $this->setPersistent(false);
                        $this->setData((object)['name' => $fields[2], 'idLanguage' => $idLanguage, 'idPOS' => $idPOS]);
                        parent::save();
                        $idLexeme = $this->getId();
                    }
                    $w = str_replace("'","\'", $fields[0]);
                    $wordform = $wf->getCriteria()->select('idWordform')
                            ->where("(form = '{$w}') and (idLexeme = {$idLexeme})")->asQuery()->getResult();
                    $idWordform = $wordform[0]['idWordform'];
                    if ($idWordform == '') {
                        $wf->setPersistent(false);
                        $wf->setData((object)['form' => $fields[0], 'idLexeme' => $idLexeme]);
                        $wf->save();
                    }
                }
            }
            $transaction->commit();
        } catch (\EModelException $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage() . ' LineNum: '. $lineNum);
        }
        return $result;
    }
    
}


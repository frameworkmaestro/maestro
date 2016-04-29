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

class Document extends map\DocumentMap
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(
                'entry' => array('notnull'),
                'author' => array('notnull'),
                'timeline' => array('notnull'),
                'idGenre' => array('notnull'),
                'idCorpus' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription()
    {
        return $this->getIdDocument();
    }

    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idDocument = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idDocument = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*,entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idDocument) {
            $criteria->where("idDocument = {$filter->idDocument}");
        }
        return $criteria;
    }

    public function listForLookup($name)
    {
        $criteria = $this->getCriteria()->select('idDocument,entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($name != '*') {
            $name = (strlen($name) > 1) ? $name : 'none';
            $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        }
        return $criteria;
    }

    public function listByCorpus($idCorpus)
    {
        $criteria = $this->getCriteria()->select('idDocument, entry, entries.name as name, count(paragraphs.sentences.idSentence) as quant')->orderBy('entries.name');
        $criteria->setAssociationType('paragraphs.sentences', 'left');
        $criteria->setAssociationType('paragraphs', 'left');
        Base::entryLanguage($criteria);
        $criteria->where("idCorpus = {$idCorpus}");
        $criteria->groupBy("idDocument, entry, entries.name");
        return $criteria;
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("entry = '{$entry}'");
        $this->retrieveFromCriteria($criteria);
    }

    public function getByName($name, $idLanguage)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("entries.idLanguage = '{$idLanguage}'");
        $criteria->where("entries.name = '{$name}'");
        $this->retrieveFromCriteria($criteria);
    }

    public function getRelatedSubCorpus()
    {
        $criteria = $this->getCriteria()->select('paragraphs.sentences.annotationsets.subcorpus.idSubCorpus');
        $criteria->where("paragraphs.sentences.annotationsets.subcorpus.name = 'document-related'");
        $criteria->where("idDocument = {$this->getId()}");
        $result = $criteria->asQuery()->getResult();
        $idSubCorpus = $result[0]['idSubCorpus'];
        return $idSubCorpus;
    }

    public function save($data)
    {
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entry = new Entry();
                $entry->newEntry($this->getEntry());
            }
            $this->setIdGenre(1); // not informed
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function createParagraph($paragraphNum = 1)
    {
        $paragraph = new Paragraph();
        $paragraph->setIdDocument($this->getIdDocument());
        $paragraph->setDocumentOrder($paragraphNum);
        $paragraph->save();
        return $paragraph;
    }

    public function createSentence($paragraph, $order, $text, $idLanguage)
    {
        $sentence = new Sentence();
        if (substr($text, 0, 1) == ':') {
            $text = substr($text, 1);
        }
        $sentence->setText($text);
        $sentence->setParagraphOrder($order);
        $sentence->setIdParagraph($paragraph->getId());
        $sentence->setIdLanguage($idLanguage);
        $sentence->save();
        return $sentence;
    }

    /**
     * Upload FullText - plain text (without processing) - UTF8
     * @param type $data
     * @param type $file
     * @return type
     * @throws EModelException
     */
    public function uploadFullText($data, $file)
    {
        $idLanguage = $data->idLanguage;
        $transaction = $this->beginTransaction();
        try {
            $this->createSubCorpusFullText($data);
            $breakParagraph = $breakSentence = false;
            $p = $paragraphNum = $sentenceNum = 0;
            $text = '';
            $rows = file($file->getTmpName());
            foreach ($rows as $row) {
                $row = str_replace("\t", " ", $row);
                $row = str_replace("\n", " ", $row);
                $row = trim($row);
                if ($row == '') {
                    continue;
                }
                $paragraph = $this->createParagraph(++$paragraphNum); // cada linha do arquivo é um paragrafo
                $words = preg_split('/ /', $row);
                $wordsSize = count($words);
                if ($wordsSize == 0) {
                    continue;
                }
                $text = ''; // texto de cada sentença
                foreach ($words as $word) {
                    if ($word == '$START') {
                        continue;
                    }
                    $word = str_replace('"', "'", str_replace('<', '', str_replace('>', '', str_replace('=', ' ', str_replace('$', '', $word)))));
                    $text .= $word;
                    if (preg_match("/\.|\?|!/", $word)) { // quebra de sentença
                        if (trim($text) != '') {
                            $sentenceNum++;
                            //mdump($paragraphNum . ' - ' . $sentenceNum . ' - ' . $text);
                            $sentence = $this->createSentence($paragraph, $sentenceNum, $text, $idLanguage);
                            $data->idSentence = $sentence->getId();
                            $this->createAnnotationFullText($data);
                        }
                        $text = '';
                    } else {
                        $text .= ' ';
                    }
                }
            }
            $transaction->commit();
        } catch (\EModelException $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
        return $result;
    }

    public function createSubCorpusFullText($data)
    {
        $subCorpus = new SubCorpus();
        $subCorpus->addManualSubCorpusDocument($data);
        $data->idSubCorpus = $subCorpus->getId();
    }

    public function createAnnotationFullText($data)
    {
        $annotationSet = new AnnotationSet();
        $annotationSet->setIdSubCorpus($data->idSubCorpus);
        $annotationSet->setIdSentence($data->idSentence);
        $annotationSet->setIdAnnotationStatus('ast_manual');
        $annotationSet->save();
    }

}

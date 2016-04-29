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

class Entry extends map\EntryMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'entry' => array('notnull'),
                'name' => array('notnull'),
                'description' => array('notnull'),
                'nick' => array('notnull'),
                'idLanguage' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*, language.language')->orderBy('entry');
        if ($filter->idEntry){
            $criteria->where("idEntry = {$filter->idEntry}");
        }
        if ($filter->entry){
            $criteria->where("entry LIKE '{$filter->entry}%'");
        }
        if ($filter->idLanguage){
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        return $criteria;
    }

    public function listForExport($entry){
        $criteria = $this->getCriteria()->select('*, language.language')->orderBy('entry');
        $criteria->where("entry = '{$entry}'");
        return $criteria;
    }
    
    public function listForUpdate($filter){
        $criteria = $this->getCriteria()->select("idEntry, entry, name, concat(substr(description,1,50),'...') as shortDescription, language.language");
        if ($filter->entry){
            $criteria->where("entry = '{$filter->entry}'");
        }
        $criteria->orderBy("language.language");
        mdump($criteria->asQuery()->getResult());
        return $criteria;
    }
    
    public function getUndefinedLanguages($entry) {
        $criteria = $this->getCriteria()->select("idLanguage");
        $criteria->where("entry = '{$entry}'");
        $language = new Language();
        $languages = $language->getCriteria()->select("idLanguage, language")
                ->where('idLanguage','not in', $criteria)
                ->asQuery()->chunkResult('idLanguage', 'language');        
        return $languages;
    }
    
    public function newEntry($entry){
        $languages = Base::languages();
        foreach($languages as $idLanguage=>$language) {
            $this->setPersistent(false);
            $this->setEntry($entry);
            $this->setName($entry);
            $this->setDescription($entry);
            $this->setNick($entry);
            $this->setIdLanguage($idLanguage);
            $this->save();
        }
    }
    
    public function updateEntry($oldEntry, $newEntry){
        $criteria = $this->getUpdateCriteria();
        $criteria->addColumnAttribute('entry');
        $criteria->where("entry = '{$oldEntry}'");
        $criteria->update($newEntry);
    }
    
    public function deleteEntry($entry){
        $criteria = $this->getDeleteCriteria();
        $criteria->addColumnAttribute('entry');
        $criteria->where("entry = '{$entry}'");
        $criteria->delete();
    }

    public function cloneEntry($sourceEntry, $targetEntry){
        $criteria = $this->getCriteria()->select("idEntry, name, description, nick, idLanguage");
        $criteria->where("entry = '{$sourceEntry}'");
        $criteria->asQuery()->each(function($row) use ($targetEntry) {
            $entry = new Entry();
            $entry->setEntry($targetEntry);
            $entry->setName($row['name']);
            $entry->setDescription($row['description']);
            $entry->setNick($row['nick']);
            $entry->setIdLanguage($row['idLanguage']);
            $entry->save();
        });
    }
    
    public function createFromData($entry){
        // get idLanguage
        $idLanguage = Base::getIdLanguage($entry->language);
        if ($idLanguage != '') {
            $this->setPersistent(false);
            $this->setEntry($entry->entry);
            $this->setName($entry->name);
            $this->setDescription($entry->description);
            $this->setNick($entry->nick);
            $this->setIdLanguage($idLanguage);
            $this->save();
        }
    }    
    
    public function addLanguage($entry, $idLanguage){
        $this->setPersistent(false);
        $this->setEntry($entry);
        $this->setName($entry);
        $this->setDescription($entry);
        $this->setNick($entry);
        $this->setIdLanguage($idLanguage);
        $this->save();
    }
    
    
}


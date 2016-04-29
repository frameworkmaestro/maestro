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

class ViewConstructionElement extends map\ViewConstructionElementMap {

    public static function config()
    {
        return [];
    }


    public function listSiblingsCE($idCE)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, e.name
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
            INNER JOIN (
            SELECT idConstruction
            FROM View_ConstructionElement
            WHERE (idConstructionElement = {$idCE})) ce1 on (ce.idConstruction = ce1.idConstruction)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.idConstructionElement <> {$idCE})
HERE;
        return $this->getDb()->getQueryCommand($cmd);

    }

    public function listCEByConstructionEntity($idEntityCxn)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, e.name
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.constructionIdEntity = {$idEntityCxn})
HERE;
        return $this->getDb()->getQueryCommand($cmd);

    }

    public function listCEByIdConstruction($idCxn)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, ce.idEntity, e.name, e.nick
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.idConstruction = {$idCxn})
HERE;
        return $this->getDb()->getQueryCommand($cmd);
    }

}


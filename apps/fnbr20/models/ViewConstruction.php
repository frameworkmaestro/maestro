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

class ViewConstruction extends map\ViewConstructionMap
{
    public static function config()
    {
        return [];
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idConstruction, entry, active, idEntity, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstruction) {
            $criteria->where("idConstruction = {$filter->idConstruction}");
        }
        if ($filter->construction) {
            $criteria->where("entries.name LIKE '{$filter->construction}%'");
        }
        if ($filter->ce) {
            $criteria->distinct(true);
            $criteria->associationAlias("ces.entries", "ceEntries");
            Base::entryLanguage($criteria,"ceEntries.");
            $criteria->where("ceEntries.name LIKE '{$filter->ce}%'");
        }
        return $criteria;
    }

}

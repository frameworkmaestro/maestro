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

class ViewFrameElement extends map\ViewFrameElementMap {

    public static function config()
    {
        return [];
    }

    public function relations($idFrameElement = '', $relationType = '', $relationGroup = '')
    {
        $criteria = $this->getCriteria()->select('vr.*');
        $criteria->join('ViewFrameElement','ViewRelation vr', "(vr.idEntity1 = ViewFrameElement.idEntity) or (vr.idEntity2 = ViewFrameElement.idEntity) or (vr.idEntity3 = ViewFrameElement.idEntity)");
        if ($idFrameElement != '') {
            $criteria->where("idFrameElement = {$idFrameElement}" );
        }
        if ($relationType != '') {
            $criteria->where("vr.relationType = '{$relationType}'" );
        }
        if ($relationGroup != '') {
            $criteria->where("vr.relationGroup = '{$relationGroup}'" );
        }
        return $criteria;
    }

    public function hasAnnotations($idFrameElement) {
        $criteria = $this->getCriteria()->select('labels.*');
        $criteria->where("idFrameElement = {$idFrameElement}" );
        $criteria->where("idEntity = labels.idLabelType" );
        $count = $criteria->asQuery()->count();
        return ($count > 0);
    }

    public function getByIdEntity($idEntity) {
        $criteria = $this->getCriteria()->select('*,entries.name as name, entries.nick as nick, frameEntries.name as feName');
        $criteria->where("idEntity = {$idEntity}");
        $criteria->associationAlias("frame.entries", "frameEntries");
        Base::entryLanguage($criteria);
        return (object)$criteria->asQuery()->getResult()[0];
    }

}


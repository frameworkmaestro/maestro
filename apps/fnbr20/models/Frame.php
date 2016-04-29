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

class Frame extends map\FrameMap
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(
                'entry' => array('notnull'),
                'active' => array('notnull'),
                'idEntity' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription()
    {
        return $this->getEntry();
    }

    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idFrame = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idFrame = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idFrame, entry, active, idEntity, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idFrame) {
            $criteria->where("idFrame = {$filter->idFrame}");
        }
        if ($filter->lu) {
            $criteria->distinct(true);
            Base::relation($criteria, 'lu', 'frame', 'rel_evokes');
            $criteria->where("lu.name LIKE '{$filter->lu}%'");
        }
        if ($filter->fe) {
            $criteriaFE = FrameElement::getCriteria();
            $criteriaFE->select('frame.idFrame, entries.name as name');
            $criteriaFE->where("entries.name LIKE '{$filter->fe}%'");
            Base::entryLanguage($criteriaFE);
            Base::relation($criteriaFE, 'frameelement', 'frame', 'rel_elementof');
            $criteria->distinct(true);
            $criteria->tableCriteria($criteriaFE, 'fe');
            $criteria->where("idFrame = fe.idFrame");
        }
        if ($filter->frame) {
            $criteria->where("entries.name LIKE '{$filter->frame}%'");
        }
        if ($filter->idLU) {
            Base::relation($criteria, 'lu', 'frame', 'rel_evokes');
            if (is_array($filter->idLU)) {
                $criteria->where("lu.idLU", "IN", $filter->idLU);
            } else {
                $criteria->where("lu.idLU = {$filter->idLU}");
            }
        }
        return $criteria;
    }

    public function listForExport($idFrames)
    {
        $criteria = $this->getCriteria()->select('idFrame, entry, active, idEntity')->orderBy('entry');
        $criteria->where("idFrame", "in", $idFrames);
        return $criteria;
    }

    public function listForLookupName($name = '')
    {
        $criteria = $this->getCriteria()->select('idFrame,entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $name = (strlen($name) > 1) ? $name: 'none';
        $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        return $criteria;
    }

    public function listFE()
    {
        $fe = new FrameElement();
        $criteria = $fe->getCriteria()->select('idFrameElement, entry, entries.name as name, typeinstance.entry as coreType, color.rgbFg, color.rgbBg, ' .
                'typeinstance.idTypeInstance as idCoreType, color.idColor');
        Base::entryLanguage($criteria);
        Base::relation($criteria, 'frameelement', 'frame', 'rel_elementof');
        Base::relation($criteria, 'frameelement', 'typeinstance', 'rel_hastype');
        $criteria->where("frame.idFrame = {$this->idFrame}");
        $criteria->orderBy('typeinstance.idTypeInstance, entries.name');
        return $criteria;
    }

    public function listAll($idLanguage)
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listDirectRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedFrame.name, relatedFrame.idEntity, relatedFrame.idFrame
        FROM Frame
            INNER JOIN Entity entity1
                ON (Frame.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Frame relatedFrame
                ON (entity2.idEntity = relatedFrame.idEntity)
            INNER JOIN Entry entry_relatedFrame
                ON (relatedFrame.entry = entry_relatedFrame.entry)
        WHERE (Frame.idFrame = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedFrame.name
            
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame');
        return $result;
    }

    public function listInverseRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedFrame.name, relatedFrame.idEntity, relatedFrame.idFrame
        FROM Frame
            INNER JOIN Entity entity2
                ON (Frame.idEntity = entity2.idEntity)
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN Frame relatedFrame
                ON (entity1.idEntity = relatedFrame.idEntity)
            INNER JOIN Entry entry_relatedFrame
                ON (relatedFrame.entry = entry_relatedFrame.entry)
        WHERE (Frame.idFrame = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedFrame.name
            
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame');
        return $result;
    }

    public function registerTemplate($idTemplate)
    {
        $template = new Template($idTemplate);
        $fes = $template->listFEforNewFrame()->asQuery()->getResult();
        Base::createEntityRelation($this->getIdEntity(), 'rel_hastemplate', $template->getIdEntity());
        $frameElement = new FrameElement();
        foreach ($fes as $fe) {
            $newFE = new \StdClass();
            $newFE->entry = $this->getEntry() . '_' . $fe['entry'] . '_' . $template->getEntry();
            $newFE->idCoreType = $fe['idCoreType'];
            $newFE->idColor = $fe['idColor'];
            $newFE->idEntity = $fe['idEntity'];
            $newFE->idFrame = $this->getId();
            $frameElement->setPersistent(false);
            $frameElement->setData($newFE);
            $frameElement->save($newFE);
            Base::createEntityRelation($frameElement->getIdEntity(), 'rel_hastemplate', $newFE->idEntity);
        }
    }

    public function save($data)
    {
        $transaction = $this->beginTransaction();
        try {
            $entity = new Entity();
            $entity->setAlias($this->getEntry());
            $entity->setType('FR');
            $entity->save();
            $entry = new Entry();
            $entry->newEntry($this->getEntry());
            $this->setIdEntity($entity->getId());
            $this->setActive(true);
            parent::save();
            if ($data->idTemplate) {
                $this->registerTemplate($data->idTemplate);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    public function delete() {
        $transaction = $this->beginTransaction();
        try {
            $idEntity = $this->getIdEntity();
            // remove entry
            $entry = new Entry();
            $entry->deleteEntry($this->getEntry());
            // remove frame-relations
            Base::deleteAllEntityRelation($idEntity);
            // remove this frame
            parent::delete();
            // remove entity
            $entity = new Entity($idEntity);
            $entity->delete();
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
            $entity = new Entity($this->getIdEntity());
            $entity->setAlias($newEntry);
            $entity->save();
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

    public function getRelations($empty = false)
    {
        $relations = ['direct' => [], 'inverse' => []];
        if (!$empty) {
            $relations['direct'] = $this->listDirectRelations();
            $relations['inverse'] = $this->listInverseRelations();
        }
        return $relations;
    }

    public function createNew($data, $inheritsFromBase)
    {
        $relations = $this->getRelations(true);
        $transaction = $this->beginTransaction();
        try {
            $this->save($data);
            if ($data->idTemplate) {
                if ($inheritsFromBase) {
                    $template = new Template($data->idTemplate);
                    $base = $template->getBaseFrame()->asQuery()->getResult();
                    if (count($base)) {
                        $idFrameBase = $base[0]['idFrame'];
                        $frameBase = new Frame($idFrameBase);
                        $relations = $frameBase->getRelations();
                        Base::createEntityRelation($frameBase->getIdEntity(), 'rel_inheritance', $this->getIdEntity());
                    }
                }
            }
            $transaction->commit();
            return $relations;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function createFromData($frame)
    {
        $this->setPersistent(false);
        $this->setEntry($frame->entry);
        $this->setActive($frame->active);
        $this->setIdEntity($frame->idEntity);
        parent::save();
    }

}

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

class Construction extends map\ConstructionMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'entry' => array('notnull'),
                'active' => array('notnull'),
                'idEntity' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getEntry();
    }

    public function getEntryObject() {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }
    
    public function getName() {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function getNick() {
        $criteria = $this->getCriteria()->select('entries.nick as nick');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('nick');
    }

    public function listAll()
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstruction) {
            $criteria->where("idConstruction = {$filter->idConstruction}");
        }
        if ($filter->cxn) {
            $criteria->where("upper(entries.name) LIKE upper('%{$filter->cxn}%')");
        }
        return $criteria;
    }

    public function listForLookupName($name = '')
    {
        $criteria = $this->getCriteria()->select('idConstruction,entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $name = (strlen($name) > 1) ? $name: 'none';
        $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        return $criteria;
    }

    public function listCE()
    {
        $ce = new ConstructionElement();
        $criteria = $ce->getCriteria()->select('idConstructionElement, entry, entries.name as name, color.rgbFg, color.rgbBg, color.idColor');
        Base::entryLanguage($criteria);
        Base::relation($criteria, 'constructionelement', 'construction', 'rel_elementof');
        $criteria->where("construction.idConstruction = {$this->getId()}");
        $criteria->orderBy('entries.name');
        return $criteria;
    }

    
    public function listDirectRelations(){
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedCxn.name, relatedCxn.idEntity, relatedCxn.idConstruction
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Construction relatedCxn
                ON (entity2.idEntity = relatedCxn.idEntity)
            INNER JOIN Entry entry_relatedCxn
                ON (relatedCxn.entry = entry_relatedCxn.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_inheritance_cxn'))
           AND (entry_relatedCxn.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedCxn.name
            
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idConstruction');
        return $result;

    }
    
    public function listInverseRelations(){
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedCxn.name, relatedCxn.idEntity, relatedCxn.idConstruction
        FROM Construction
            INNER JOIN Entity entity2
                ON (Construction.idEntity = entity2.idEntity)
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN Construction relatedCxn
                ON (entity1.idEntity = relatedCxn.idEntity)
            INNER JOIN Entry entry_relatedCxn
                ON (relatedCxn.entry = entry_relatedCxn.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_inheritance_cxn' ))
           AND (entry_relatedCxn.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedCxn.name
            
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idConstruction');
        return $result;

    }

    public function listEvokesRelations(){
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedFrame.name, entry_relatedFrame.nick, relatedFrame.idEntity, relatedFrame.idFrame
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
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
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_evokes'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedFrame.name
            
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame');
        return $result;

    }

    public function getStructure() {
        $structure = [];
        $idEntity = $this->getIdEntity();
        $structure[] = [
            'idEntity' => $idEntity,
            'name' => $this->getName(),
            'nick' => $this->getNick() ?: $this->getName(),
            'typeSystem' => 'CX',
            'source' => '',
            'label' => ''
        ];

        $vce = new ViewConstructionElement();
        $ces = $vce->listCEByIdConstruction($this->getId())->getResult();
        $vc = new ViewConstraint();
        foreach($ces as $ce) {
            $structure[] = [
                'idEntity' => $ce['idEntity'],
                'name' => $ce['name'],
                'nick' => $ce['name'], //$ce['nick'] ?: $ce['name'],
                'typeSystem' => 'CE',
                'source' => $idEntity,
                'label' => 'rel_elementof'
            ];
            $chain = [];
            $vc->getChainByIdConstrained($ce['idEntity'], $ce['idEntity'], $chain);
            foreach($chain as $constraint) {
                $structure[] = [
                    'idEntity' => $constraint['idConstrainedBy'],
                    'name' => $constraint['name'],
                    'nick' => $constraint['nick'] ?: $constraint['name'],
                    'typeSystem' => $constraint['type'],
                    'source' => $constraint['idConstrained'],
                    'label' => 'rel_elementof'
                ];
            }
        }
        $er = new EntityRelation();
        $vfe = new ViewFrameElement();
        $evokes = $this->listEvokesRelations();
        foreach($evokes['rel_evokes'] as $frame) {
            $structure[] = [
                'idEntity' => $frame['idEntity'],
                'name' => $frame['name'],
                'nick' => $frame['nick'] ?: $frame['name'],
                'typeSystem' => 'FR',
                'source' => $idEntity,
                'label' => 'rel_evokes'
            ];
            $cefeRelation = $er->listCEFERelations($idEntity,$frame['idEntity'], 'rel_evokes')->asQuery()->getResult();
            foreach($cefeRelation as $relation) {
                $fe = $vfe->getByIdEntity($relation['idEntity2']);
                $structure[] = [
                    'idEntity' => $fe->idEntity,
                    'name' => $fe->feName . '.' . $fe->name,
                    'nick' => $fe->feName . '.' . $fe->name,
                    'typeSystem' => 'FE',
                    'source' => $fe->frameIdEntity,
                    'label' => 'rel_elementof'
                ];
                $structure[] = [
                    'idEntity' => $fe->idEntity,
                    'name' => $fe->feName . '.' . $fe->name,
                    'nick' => $fe->feName . '.' . $fe->name,
                    'typeSystem' => 'FE',
                    'source' => $relation['idEntity1'],
                    'label' => 'rel_evokes'
                ];
            }
        }

        return $structure;
    }
    
    public function save($data)
    {
        $transaction = $this->beginTransaction();
        try {
            $entity = new Entity();
            $entity->setAlias($this->getEntry());
            $entity->setType('CX');
            $entity->save();
            $entry = new Entry();
            $entry->newEntry($this->getEntry());
            $this->setIdEntity($entity->getId());
            $this->setActive(true);
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
    
    public function createNew($data)
    {
        $transaction = $this->beginTransaction();
        try {
            $this->save($data);
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
    
}


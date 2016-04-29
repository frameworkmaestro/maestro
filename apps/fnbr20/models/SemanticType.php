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

class SemanticType extends map\SemanticTypeMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'entry' => array('notnull'),
                'idEntity' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription()
    {
        return $this->getEntry();
    }

    public function getEntryObject() {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idSemanticType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }
    
    public function getName() {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idSemanticType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idSemanticType, entry, idEntity, idDomain, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idSemanticType) {
            $criteria->where("idSemanticType = {$filter->idSemanticType}");
        }
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->type) {
            $criteria->where("upper(entries.name) LIKE upper('{$filter->type}%')");
        }
        return $criteria;
    }

    public function listRoot($filter)
    {
        $criteria = $this->getCriteria()->select('idSemanticType, entry, idEntity, idDomain, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idSemanticType) {
            $criteria->where("idSemanticType = {$filter->idSemanticType}");
        }
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->type) {
            $criteria->where("upper(entries.name) LIKE upper('{$filter->type}%')");
        }
        $entityRelation = new EntityRelation();
        $criteriaER = $entityRelation->getCriteria()
                ->select('idEntity1')
                ->where("relationtype.entry = 'rel_subtypeof'");
        $criteria->where("idEntity","NOT IN", $criteriaER);
        return $criteria;
    }
    
    public function listChildren($idSuperType, $filter)
    {
        $criteria = $this->getCriteria()->select('idSemanticType, entry, idEntity, idDomain, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idSemanticType) {
            $criteria->where("idSemanticType = {$filter->idSemanticType}");
        }
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->type) {
            $criteria->where("upper(entries.name) LIKE upper('{$filter->type}%')");
        }
        $superType = new SemanticType($idSuperType);
        $entityRelation = new EntityRelation();
        $criteriaER = $entityRelation->getCriteria()
                ->select('idEntity1')
                ->where("relationtype.entry = 'rel_subtypeof'")
                ->where("idEntity2 = {$superType->getIdEntity()}");
        $criteria->where("idEntity","IN", $criteriaER);
        return $criteria;
    }

    public function listAll($idLanguage)
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }
    
    public function listForLookup($filter)
    {
        $criteria = $this->getCriteria()->select("idSemanticType,concat(entries.name, '.',  dEntries.name) as name")->orderBy('concat(entries.name, dEntries.name)');
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        $criteria->associationAlias("domain.entries", "dEntries");
        Base::entryLanguage($criteria,"dEntries.");
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listTypesByEntity($idEntity)
    {
        $domain = new Domain();
        $domainCriteria = $domain->getCriteria()
                ->select('domain.idDomain, domain.entries.name as domainName')
                ->setAlias('d');
        Base::entryLanguage($domainCriteria,'domain');
        $criteria = Base::relationCriteria('entity', 'semantictype', 'rel_hassemtype', 
                'semantictype.idSemanticType,semantictype.entries.name as name,semantictype.idEntity, d.domainName')
                ->orderBy('semantictype.entries.name');
        $criteria->joinCriteria($domainCriteria,"(d.idDomain = semantictype.idDomain)");
        $criteria->where('entity.idEntity','=',$idEntity);
        Base::entryLanguage($criteria,'semantictype');
        return $criteria;
    }

    public function save($data)
    {
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('ST');
                $entity->save();
                $entry = new Entry();
                $entry->newEntry($this->getEntry());
                $this->setIdEntity($entity->getId());
                if ($data->idSuperType) {
                    $superType = new SemanticType($data->idSuperType);
                    $this->setIdDomain($superType->getIdDomain());
                    Base::createEntityRelation($entity->getId(), 'rel_subtypeof', $superType->getIdEntity());
                }
            }
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        $transaction = $this->beginTransaction();
        try {
            $hasChildren = (count($this->listChildren($this->getId())->asQuery()->getResult()) > 0);
            if ($hasChildren) {
                throw new \Exception("Type has subtypes; it can't be removed.");
            } else {
                Base::deleteAllEntityRelation($this->getIdEntity());
                parent::delete();
                $entity = new Entity($this->getIdEntity());
                $entity->delete();
                $entry = new Entry();
                $entry->deleteEntry($this->getEntry());
                $transaction->commit();
            }
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
    
    public function addEntity($idEntity) {
        Base::createEntityRelation($idEntity, 'rel_hassemtype', $this->getIdEntity());
    }

    public function delSemanticTypeFromEntity($idEntity, $idSemanticTypeEntity = []) {
        $rt = new RelationType();
        $c = $rt->getCriteria()->select('idRelationType')->where("entry = 'rel_hassemtype'");        
        $er = new EntityRelation();
        $transaction = $er->beginTransaction();
        $criteria = $er->getDeleteCriteria();
        $criteria->where("idEntity1 = {$idEntity}");
        $criteria->where("idEntity2","IN",$idSemanticTypeEntity);
        $criteria->where("idRelationType", "=", $c);
        $criteria->delete();
        $transaction->commit();        
    }
    
}


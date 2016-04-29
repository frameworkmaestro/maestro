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

class ConstructionElement extends map\ConstructionElementMap {

    public static function config() {
        return array(
            'log' => array(  ),
            'validators' => array(
                'entry' => array('notnull'),
                'active' => array('notnull'),
                'idEntity' => array('notnull'),
                'idColor' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getIdConstructionElement();
    }
    
    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idConstructionElement = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function getConstruction() {
        $vc = new ViewConstruction();
        $criteria = $vc->getCriteria()->select('idConstruction')->where("ces.idConstructionElement = {$this->getId()}");
        return Construction::create($criteria->asQuery()->getResult()[0]['idConstruction']);
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idConstructionElement');
        if ($filter->idConstructionElement){
            $criteria->where("idConstructionElement LIKE '{$filter->idConstructionElement}%'");
        }
        if ($filter->idConstruction) {
            Base::relation($criteria, 'constructionelement', 'construction', 'rel_elementof');
            $criteria->where("construction.idConstruction = {$filter->idConstruction}");
        }          
        return $criteria;
    }
    
    public function listForEditor($idEntityCxn)
    {
        $criteria = $this->getCriteria()->select('idEntity,entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        Base::relation($criteria, 'constructionelement', 'construction', 'rel_elementof');
        $criteria->where("construction.idEntity = {$idEntityCxn}");
        return $criteria;
    }

    public function listSiblingsCE()
    {
        $view = new ViewConstructionElement();
        $query = $view->listSiblingsCE($this->getId());
        return $query;
    }

    public function listConstraints()
    {
        $constraint = new ViewConstraint();
        return $constraint->getByIdConstrained($this->getIdEntity());
    }


    public function getStylesByCxn($idConstruction)
    {
        $criteria = $this->getCriteria()->select('idConstructionElement, entry, entries.name as name, color.rgbFg, color.rgbBg');
        Base::entryLanguage($criteria);
        Base::relation($criteria, 'constructionelement', 'construction', 'rel_elementof');
        $criteria->where("idConstruction = '{$idConstruction}'");
        $result = $criteria->asQuery()->getResult();
        $styles = [];
        foreach ($result as $ce) {
            $name = strtolower($ce['name']);//
            $styles[$name] = ['ce' => $name, 'rgbFg' => $ce['rgbFg'], 'rgbBg' => $ce['rgbBg']];
        }
        return $styles;
    }


    public function listForReport($idConstruction = '')
    {
        $criteria = $this->getCriteria()->select('idConstructionElement,entries.name as name, entries.description as description, entries.nick as nick')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($idConstruction) {
            Base::relation($criteria, 'constructionelement', 'construction', 'rel_elementof');
            $criteria->where("construction.idConstruction = {$idConstruction}");
        }
        return $criteria;
    }


    public function save($data)
    {
        $transaction = $this->beginTransaction();
        try {
            if ($this->isPersistent()) {
                $this->setActive(true);
            } else {
                $schema = new Construction($data->idConstruction);
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('CE');
                $entity->save();
                $entry = new Entry();
                $entry->newEntry($this->getEntry());
                Base::createEntityRelation($entity->getId(), 'rel_elementof', $schema->getIdEntity());
                $this->setIdEntity($entity->getId());
                $this->setActive(true);
            }
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    
    public function saveModel(){
        parent::save();
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

    public function delete() {
        $transaction = $this->beginTransaction();
        try {
            $idEntity = $this->getIdEntity();
            // remove entry
            $entry = new Entry();
            $entry->deleteEntry($this->getEntry());
            // remove ce-relations
            Base::deleteAllEntityRelation($idEntity);
            // remove this ce
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


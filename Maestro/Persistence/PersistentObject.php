<?php

/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

namespace Maestro\Persistence;

use Maestro,
    Maestro\Persistence\Map\AttributeMap as AttributeMap,
    Maestro\Persistence\Criteria\RetrieveCriteria as RetrieveCriteria;

class PersistentObject
{

    private $isPersistent;
    private $manager;
    protected $_className;
    private $db;

    public function __construct($configLoader = 'PHP')
    {
        $this->manager = PersistentManager::getInstance($configLoader);
    }

    public function getClassMap()
    {
        return $this->manager->getClassMap($this->_className);
    }

    public function setPersistent($value)
    {
        $this->isPersistent = $value;
    }

    public function isPersistent()
    {
        return $this->isPersistent;
    }

    public function setAttributeValue(AttributeMap $attributeMap, $value)
    {
        $attributeMap->setValue($this, $value);
    }

    public function getAttributeValue(AttributeMap $attributeMap)
    {
        return $attributeMap->getValue($this);
    }

    public function retrieve()
    {
        $this->manager->retrieveObject($this);
    }

    public function retrieveFromQuery($query)
    {
        $this->manager->retrieveObjectFromQuery($this, $query);
    }

    public function retrieveFromCriteria($criteria, $parameters = NULL)
    {
        $this->manager->retrieveObjectFromCriteria($this, $criteria, $parameters);
    }

    public function retrieveAssociation($association, $orderAttributes = '')
    {
        $this->manager->retrieveAssociation($this, $association);
    }

    public function retrieveAssociationAsCursor($association, $orderAttribues = '')
    {
        $this->manager->retrieveAssociationAsCursor($this, $association);
    }

    public static function find($select = '*', $where = '', $orderBy = '')
    {
        $className = get_called_class();
        $classMap = PersistentManager::getInstance()->getClassMap($className);
        $criteria = new RetrieveCriteria($classMap);
        $criteria->select($select)->where($where)->orderBy($orderBy);
        return $criteria;
    }

    public function getCriteria($command = '')
    {
        return $this->manager->getRetrieveCriteria($this, $command);
    }

    public function getDeleteCriteria()
    {
        return $this->manager->getDeleteCriteria($this);
    }

    public function getUpdateCriteria()
    {
        return $this->manager->getUpdateCriteria($this);
    }

    public function update()
    {
        $this->manager->saveObjectRaw($this);
    }

    public function save()
    {
        $this->manager->saveObject($this);
    }

    public function saveAssociation($association)
    {
        $this->manager->saveAssociation($this, $association);
    }

    public function saveAssociationById($association, $id)
    {
        $this->manager->saveAssociationById($this, $association, $id);
    }

    public function delete()
    {
        $this->manager->deleteObject($this);
    }

    public function deleteAssociation($association)
    {
        $this->manager->deleteAssociation($this, $association);
    }

    public function deleteAssociationObject($association, $object)
    {
        $this->manager->deleteAssociationObject($this, $association, $object);
    }

    public function deleteAssociationById($association, $id)
    {
        $this->manager->deleteAssociationById($this, $association, $id);
    }

    public function handleLOBAttribute($attribute, $value, $operation)
    {
        $this->manager->handleLOBAttribute($this, $attribute, $value, $operation);
    }

    public function getPKName($index = 0)
    {
        return $this->getClassMap()->getKeyAttributeName($index);
    }

    public function getOIDName()
    {
        return $this->getPKName();
    }

    public function getPKValue($index = 0)
    {
        $pk = $this->getPKName($index);
        return $this->get($pk);
    }

    public function getOIDValue()
    {
        return $this->getPKValue();
    }

    public function getId()
    {
        return $this->getPKValue();
    }

    public function getDatabaseName()
    {
        return $this->getClassMap()->getDatabaseName();
    }

    public function getColumnName($attributeName)
    {
        return $this->getClassMap()->getAttributeMap($attributeName)->getColumnName();
    }

    public function getDb()
    {
        return $this->getClassMap()->getDb();
    }

    // compatibilidade
    public function getValue($attribute)
    {
        return $this->manager->getValue($this, $attribute);
    }

}

?>
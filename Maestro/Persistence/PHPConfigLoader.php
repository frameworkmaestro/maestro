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

use Maestro;

/**
 * Brief Class Description.
 * Complete Class Description.
 */
class PHPConfigLoader
{

    private $manager;
    private $phpMaps = array();
    private $classMaps = array();

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function getLocation($className)
    {
        return array();
    }

    public function getMap($className)
    {
        $p = strrpos($className, '\\');
        if ($p === false) {
            return;
        }
        if (!isset($this->phpMaps[$className])) {
            //$classNameMap = substr($className, 0, $p) . "\\map" . substr($className, $p) . 'map';
            //mdump('-----------------'.$classNameMap);
            $this->phpMaps[$className] = $className::ORMMap();
        }
        return $this->phpMaps[$className];
    }

    public function getClassMap($className)
    {
        if ($className == '') {mtracestack();}
        $classIndex = strtolower(trim($className));
        if ($className{0} == '\\') {
            $className = substr($className, 1);
        }
        if ($className == '') {
            return;
        }
        if (isset($this->classMaps[$classIndex])) {
            return $this->classMaps[$classIndex];
        }
        $map = $this->getMap($className);
        $database = $map['database'];
        $classMap = new \Maestro\Persistence\Map\ClassMap($className, $database);
        $classMap->setDatabaseName($database);
        $classMap->setTableName($map['table']);

        if (isset($map['extends'])) {
            $classMap->setSuperClassName($map['extends']);
        }

        $config = $className::config();

        $attributes = $map['attributes'];
        $referenceAttribute = false;
        foreach ($attributes as $attributeName => $attr) {
            $attributeMap = new \Maestro\Persistence\Map\AttributeMap($attributeName, $classMap);
            if (isset($attr['index'])) {
                $attributeMap->setIndex($attr['index']);
            }

            $type = isset($attr['type']) ? strtolower($attr['type']) : 'string';
            $attributeMap->setType($type);
            $plataformTypedAttributes = $classMap->getDb()->getPlatform()->getTypedAttributes();
            $attributeMap->setHandled(strpos($plataformTypedAttributes, $type) !== false);
            if (isset($config['converters'][$attributeName])) {
                $attributeMap->setConverter($config['converters'][$attributeName]);
            }

            $attributeMap->setColumnName(isset($attr['column']) ? $attr['column'] : $attributeName);
            $attributeMap->setAlias(isset($attr['alias']) ? $attr['alias'] : $attributeName);
            $attributeMap->setKeyType(isset($attr['key']) ? $attr['key'] : 'none');
            $attributeMap->setIdGenerator(isset($attr['idgenerator']) ? $attr['idgenerator'] : null);

            if (isset($attr['key']) && ($attr['key'] == 'reference') && ($classMap->getSuperClassMap() != null)) {
                $referenceAttribute = $classMap->getSuperClassMap()->getAttributeMap($attributeName);
                if ($referenceAttribute) {
                    $attributeMap->setReference($referenceAttribute);
                }
            }
            $classMap->addAttributeMap($attributeMap);
        }

        $this->classMaps[$classIndex] = $classMap;

        if ($referenceAttribute) {
            // set superAssociationMap
            $attributeName = $referenceAttribute->getName();
            $superClassName = $classMap->getSuperClassMap()->getName();
            $superAssociationMap = new \Maestro\Persistence\Map\AssociationMap($classMap, $superClassName);
            $superAssociationMap->setToClassName($superClassName);
            $superAssociationMap->setToClassMap($classMap->getSuperClassMap());
            $superAssociationMap->setCardinality('oneToOne');
            $superAssociationMap->addKeys($attributeName, $attributeName);
            $superAssociationMap->setKeysAttributes();
            $classMap->setSuperAssociationMap($superAssociationMap);
        }

        $associations = $map['associations'];
        if (isset($associations)) {

            $fromClassMap = $classMap;
            foreach ($associations as $associationName => $association) {
                $toClass = $association['toClass'];
                $associationMap = new \Maestro\Persistence\Map\AssociationMap($classMap, $associationName);
                $associationMap->setToClassName($toClass);
                $associationMap->setDeleteAutomatic($association['deleteAutomatic']);
                $associationMap->setSaveAutomatic($association['saveAutomatic']);
                $associationMap->setRetrieveAutomatic($association['retrieveAutomatic']);
                $autoAssociation = (strtolower($className) == strtolower($toClass));
                if (!$autoAssociation) {
                    $autoAssociation = (strtolower($className) == strtolower(substr($toClass, 1)));
                }
                $associationMap->setAutoAssociation($autoAssociation);
                if (isset($association['index'])) {
                    $associationMap->setIndexAttribute($association['index']);
                }
                $associationMap->setCardinality($association['cardinality']);
                if ($association['cardinality'] == 'manyToMany') {
                    $associationMap->setAssociativeTable($association['associative']);
                } else {
                    $arrayKeys = explode(',', $association['keys']);
                    foreach ($arrayKeys as $keys) {
                        $key = explode(':', $keys);
                        $associationMap->addKeys($key[0], $key[1]);
                    }
                }

                if (isset($association['order'])) {
                    $order = array();
                    $orderAttributes = explode(',', $association['order']);
                    foreach ($orderAttributes as $orderAttr) {
                        $o = explode(' ', $orderAttr);
                        $ascend = (substr($o[1], 0, 3) == 'asc');
                        $order[] = array($o[0], $ascend);
                    }
                    if (count($order)) {
                        $associationMap->setOrder($order);
                    }
                }

                $fromClassMap->putAssociationMap($associationMap);
            }
        }
        return $classMap;
    }

}

?>
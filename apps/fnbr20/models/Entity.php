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

class Entity extends map\EntityMap
{

    public static $entityModel = [
        'AS' => 'typeinstance', // annotation status
        'CE' => 'constructionelement',
        'CT' => 'typeinstance', // core type
        'CX' => 'construction',
        'FE' => 'frameelement',
        'FR' => 'frame',
        'GL' => 'genericlabel',
        'IT' => 'typeinstance', // instantiation type
        'LT' => 'labeltype',
        'LU' => 'lu',
        'PS' => 'pos',
        'SC' => 'subcorpus',
        'ST' => 'semantictype'
    ];

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(
                'alias' => array('notnull'),
                'type' => array('notnull'),
                'timeline' => array('notnull'),
                'idOld' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription()
    {
        return $this->getIdEntity();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idEntity');
        if ($filter->idEntity) {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        return $criteria;
    }

    public function getTypeNode()
    {
        $typeNode = [
            'FR' => 'frame',
            'FE' => 'fe',
            'CX' => 'cxn',
            'ST' => 'st'
        ];
        return $typeNode[$this->getType()];
    }

    public function getName()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $model = self::$entityModel[$this->getType()];
        $cmd = <<<HERE
        SELECT entry.name
        FROM {$model}
            INNER JOIN entry
                ON ({$model}.entry = entry.entry)
        WHERE (entry.idLanguage = {$idLanguage} )
        and ({$model}.idEntity = {$this->getIdEntity()})
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result[0]['name'];
    }

    public function listDirectRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
        SELECT RelationType.entry, entry.name, model.idEntity, model.type
        FROM Entity entity1
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN (
		   select entry, idEntity, 'frame' as type from Frame
		   UNION select entry, idEntity, 'fe' as type from FrameElement
		   UNION select entry, idEntity, 'cxn' as type from Construction
		   UNION select entry, idEntity, 'ce' as type from ConstructionElement
		   UNION select entry, idEntity, 'st' as type from SemanticType
		) model on (entity2.idEntity = model.idEntity)
            INNER JOIN Entry 
                ON (model.entry = entry.entry)
        WHERE (entity1.idEntity = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using',
		'rel_evokes',
	        'rel_inheritance_cxn',
		'rel_hassemtype',
	        'rel_elementof'
		))
           AND (entry.idLanguage = {$idLanguage}  )
        ORDER BY RelationType.entry, entry.name
                
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,type');
        return $result;
    }

    public function listInverseRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
        SELECT RelationType.entry, entry.name, model.idEntity, model.type
        FROM Entity entity2
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN (
		   select entry, idEntity, 'frame' as type from Frame
		   UNION select entry, idEntity, 'fe' as type from FrameElement
		   UNION select entry, idEntity, 'cxn' as type from Construction
		   UNION select entry, idEntity, 'ce' as type from ConstructionElement
		   UNION select entry, idEntity, 'st' as type from SemanticType
		) model on (entity1.idEntity = model.idEntity)
            INNER JOIN Entry 
                ON (model.entry = entry.entry)
        WHERE (entity2.idEntity = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using',
		'rel_evokes',
	        'rel_inheritance_cxn',
		'rel_hassemtype',
	        'rel_elementof'
		))
           AND (entry.idLanguage = {$idLanguage}  )
        ORDER BY RelationType.entry, entry.name
                
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,type');
        return $result;
    }

    public function listElementRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
        SELECT RelationType.entry, entry.name, model.idEntity, model.type
        FROM Entity entity2
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType 
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN (
		   select entry, idEntity, 'fe' as type from FrameElement
		   UNION select entry, idEntity, 'ce' as type from ConstructionElement
		) model on (entity1.idEntity = model.idEntity)
            INNER JOIN Entry 
                ON (model.entry = entry.entry)
        WHERE (entity2.idEntity = {$this->getId()})
            AND (RelationType.entry = 'rel_elementof')
            AND (entry.idLanguage = {$idLanguage}  )
        ORDER BY RelationType.entry, entry.name
                
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,type');
        return $result;
    }

    public function listElement2ElementRelation($elements1, $elements2, $type)
    {
        mdump('--' . $type);
        $idLanguage = \Manager::getSession()->idLanguage;
        $el1 = implode(',', $elements1);
        $el2 = implode(',', $elements2);
        $cmd = <<<HERE
        SELECT entry1.name as name1, er.idEntity1, model1.type as model1,  entry2.name as name2, er.idEntity2, model2.type as model2
        FROM EntityRelation er
            INNER JOIN Entity e1
                ON (er.idEntity1 = e1.idEntity)
            INNER JOIN RelationType 
                ON (er.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity e2
                ON (er.idEntity2 = e2.idEntity)
            INNER JOIN (
		   select entry, idEntity, 'fe' as type from FrameElement
		   UNION select entry, idEntity, 'ce' as type from ConstructionElement
		) model1 on (e1.idEntity = model1.idEntity)
            INNER JOIN (
		   select entry, idEntity, 'fe' as type from FrameElement
		   UNION select entry, idEntity, 'ce' as type from ConstructionElement
		) model2 on (e2.idEntity = model2.idEntity)
            INNER JOIN Entry entry1
                ON (model1.entry = entry1.entry)
            INNER JOIN Entry entry2
                ON (model2.entry = entry2.entry)
        WHERE (e1.idEntity IN ({$el1}))
            AND (e2.idEntity IN ({$el2}))
            AND (RelationType.entry = '{$type}')
            AND (entry1.idLanguage = {$idLanguage}  )
            AND (entry2.idLanguage = {$idLanguage}  )
        ORDER BY er.idEntity1, er.idEntity2
                
HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;
    }

    public function createFromData($entity)
    {
        $this->setPersistent(false);
        $this->setAlias($entity->alias);
        $this->setType($entity->type);
        $this->setIdOld($entity->idOld);
        $this->save();
    }

}

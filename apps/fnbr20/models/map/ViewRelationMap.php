<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2013 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

// wizard - code section created by Wizard Module

namespace fnbr20\models\map;

class ViewRelationMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'view_relation',
            'attributes' => array(
                'idEntityRelation' => array('column' => 'idEntityRelation','key' => 'primary','type' => 'integer'),
                'domain' => array('domain' => 'name','type' => 'string'),
                'relationGroup' => array('relationGroup' => 'name','type' => 'string'),
                'idRelationType' => array('idRelationType' => 'name','type' => 'integer'),
                'relationType' => array('relationType' => 'name','type' => 'string'),
                'idEntity1' => array('idEntity1' => 'name','type' => 'integer'),
                'idEntity2' => array('idEntity2' => 'name','type' => 'integer'),
                'idEntity3' => array('idEntity3' => 'name','type' => 'integer'),
                'entity1Type' => array('entity1Type' => 'name','type' => 'string'),
                'entity2Type' => array('entity2Type' => 'name','type' => 'string'),
                'entity3Type' => array('entity3Type' => 'name','type' => 'string'),
            ),
            'associations' => array(
                'entity1' => array('toClass' => 'fnbr20\models\Entity', 'cardinality' => 'oneToOne' , 'keys' => 'idEntity1:idEntity'),
                'entity2' => array('toClass' => 'fnbr20\models\Entity', 'cardinality' => 'oneToOne' , 'keys' => 'idEntity2:idEntity'),
                'entity3' => array('toClass' => 'fnbr20\models\Entity', 'cardinality' => 'oneToOne' , 'keys' => 'idEntity3:idEntity'),
            )
        );
    }
    

}

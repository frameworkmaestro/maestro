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

class ViewSubCorpusCxnMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'view_subcorpuscxn',
            'attributes' => array(
                'idSubCorpus' => array('column' => 'idSubCorpus','key' => 'primary','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'rank' => array('column' => 'rank','type' => 'int'),
                'idConstruction' => array('column' => 'idConstruction','type' => 'integer'),
            ),
            'associations' => array(
                'annotationsets' => array('toClass' => 'fnbr20\models\ViewAnnotationSet', 'cardinality' => 'oneToMany' , 'keys' => 'idSubCorpus:idSubCorpus'),
                'construction' => array('toClass' => 'fnbr20\models\ViewConstruction', 'cardinality' => 'oneToOne' , 'keys' => 'idConstruction:idConstruction'),
            )
        );
    }
    

}

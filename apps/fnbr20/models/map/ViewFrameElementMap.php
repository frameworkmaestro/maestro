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

class ViewFrameElementMap extends \MBusinessModel {

    
    public static function ORMMap() {
        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'view_frameelement',
            'attributes' => array(
                'idFrameElement' => array('column' => 'idFrameElement','key' => 'primary','type' => 'integer'),
                'entry' => array('column' => 'entry','type' => 'string'),
                'active' => array('column' => 'active','type' => 'integer'),
                'idEntity' => array('column' => 'idEntity','type' => 'integer'),
                'idColor' => array('column' => 'idColor','type' => 'integer'),
                'typeEntry' => array('column' => 'typeEntry','type' => 'string'),
                'idFrame' => array('column' => 'idFrame', 'type' => 'integer'),
                'frameEntry' => array('column' => 'frameEntry','type' => 'string'),
                'frameIdEntity' => array('column' => 'frameIdEntity','type' => 'integer'),
            ),
            'associations' => array(
                'entries' => array('toClass' => 'fnbr20\models\ViewEntryLanguage', 'cardinality' => 'oneToOne' , 'keys' => 'entry:entry'),
                'frame' => array('toClass' => 'fnbr20\models\ViewFrame', 'cardinality' => 'oneToOne' , 'keys' => 'idFrame:idFrame'),
                'color' => array('toClass' => 'fnbr20\models\Color', 'cardinality' => 'oneToOne' , 'keys' => 'idColor:idColor'),
                'labels' => array('toClass' => 'fnbr20\models\Label', 'cardinality' => 'oneToMany' , 'keys' => 'idFrameElement:idLabelType'),
            )
        );
    }
    

}

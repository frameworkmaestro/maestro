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

class ViewFrameMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'view_frame',
            'attributes' => array(
                'idFrame' => array('column' => 'idFrame', 'type' => 'integer','key' => 'primary'),
                'entry' => array('column' => 'entry','type' => 'string'),
                'active' => array('column' => 'active','type' => 'integer'),
                'idEntity' => array('column' => 'idEntity','type' => 'integer'),
            ),
            'associations' => array(
                'entries' => array('toClass' => 'fnbr20\models\ViewEntryLanguage', 'cardinality' => 'oneToOne' , 'keys' => 'entry:entry'),
                'lus' => array('toClass' => 'fnbr20\models\ViewLU', 'cardinality' => 'oneToMany' , 'keys' => 'idFrame:idFrame'),
                'fes' => array('toClass' => 'fnbr20\models\ViewFrameElement', 'cardinality' => 'oneToMany' , 'keys' => 'idFrame:idFrame'),
            )
        );
    }
    

}

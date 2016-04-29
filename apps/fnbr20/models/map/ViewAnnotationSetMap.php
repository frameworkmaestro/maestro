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

class ViewAnnotationSetMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'view_annotationset',
            'attributes' => array(
                'idAnnotationSet' => array('column' => 'idAnnotationSet','key' => 'primary','type' => 'integer'),
                'idSentence' => array('column' => 'idSentence','type' => 'integer'),
                'entry' => array('column' => 'entry','type' => 'string'),
                'idAnnotationStatus' => array('column' => 'idAnnotationStatus','key' => 'foreign','type' => 'integer'),
                'idSubCorpus' => array('column' => 'idSubCorpus','key' => 'foreign','type' => 'integer'),
            ),
            'associations' => array(
                'entries' => array('toClass' => 'fnbr20\models\ViewEntryLanguage', 'cardinality' => 'oneToOne' , 'keys' => 'entry:entry'),
                'subcorpuslu' => array('toClass' => 'fnbr20\models\ViewSubCorpusLU', 'cardinality' => 'oneToOne' , 'keys' => 'idSubCorpus:idSubCorpus'),
                'subcorpuscxn' => array('toClass' => 'fnbr20\models\ViewSubCorpusCxn', 'cardinality' => 'oneToOne' , 'keys' => 'idSubCorpus:idSubCorpus'),
                'sentence' => array('toClass' => 'fnbr20\models\Sentence', 'cardinality' => 'oneToOne' , 'keys' => 'idSentence:idSentence'),
                'annotationstatustype' => array('toClass' => 'fnbr20\models\ViewAnnotationStatusType', 'cardinality' => 'oneToOne' , 'keys' => 'entry:entry'),
            )
        );
    }
    

}

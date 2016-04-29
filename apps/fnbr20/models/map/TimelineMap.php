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

class TimelineMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'Timeline',
            'attributes' => array(
                'idTimeline' => array('column' => 'idTimeline','key' => 'primary','idgenerator' => 'identity','type' => 'integer'),
                'timeline' => array('column' => 'timeline','type' => 'string'),
                'numOrder' => array('column' => 'numorder','type' => 'integer'),
                'tlDateTime' => array('column' => 'tldateTime','type' => 'timestamp'),
                'author' => array('column' => 'author','type' => 'string'),
            ),
            'associations' => array(
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idTimeline;
    /**
     * 
     * @var string 
     */
    protected $timeline;
    /**
     * 
     * @var integer 
     */
    protected $numOrder;
    /**
     * 
     * @var date 
     */
    protected $tlDateTime;
    /**
     * 
     * @var string 
     */
    protected $author;

    /**
     * Associations
     */
    

    /**
     * Getters/Setters
     */
    public function getIdTimeline() {
        return $this->idTimeline;
    }

    public function setIdTimeline($value) {
        $this->idTimeline = $value;
    }

    public function getTimeline() {
        return $this->timeline;
    }

    public function setTimeline($value) {
        $this->timeline = $value;
    }

    public function getNumOrder() {
        return $this->numOrder;
    }

    public function setNumOrder($value) {
        $this->numOrder = $value;
    }

    public function getTlDateTime() {
        return $this->tlDateTime;
    }

    public function setTlDateTime($value) {
        if (!($value instanceof \Maestro\Types\MTimeStamp)) {
            $value = new \Maestro\Types\MTimeStamp($value);
        }
        $this->tlDateTime = $value;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($value) {
        $this->author = $value;
    }

    

}
// end - wizard

?>
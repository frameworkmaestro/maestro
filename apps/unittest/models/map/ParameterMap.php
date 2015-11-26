<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage unittest
 * @copyright  Copyright (c) 2003-2013 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

// wizard - code section created by Wizard Module

namespace unittest\models\map;

class ParameterMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'unittest',
            'table' => 'Parameter',
            'attributes' => array(
                'idParameter' => array('column' => 'idParameter','key' => 'primary','idgenerator' => 'seq_parameter','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'value' => array('column' => 'value','type' => 'string'),
                'idTest' => array('column' => 'idTest','key' => 'foreign','type' => 'integer'),
                'order' => array('column' => 'order','type' => 'integer'),
            ),
            'associations' => array(
                'test' => array('toClass' => '\unittest\models\Test', 'cardinality' => 'oneToOne' , 'keys' => 'idTest:idTest'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idParameter;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     * 
     * @var string 
     */
    protected $value;
    /**
     * 
     * @var integer 
     */
    protected $idTest;
    /**
     * 
     * @var integer 
     */
    protected $order;

    /**
     * Associations
     */
    protected $test;
    

    /**
     * Getters/Setters
     */
    public function getIdParameter() {
        return $this->idParameter;
    }

    public function setIdParameter($value) {
        $this->idParameter = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getIdTest() {
        return $this->idTest;
    }

    public function setIdTest($value) {
        $this->idTest = $value;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($value) {
        $this->order = $value;
    }
    /**
     *
     * @return Association
     */
    public function getTest() {
        if (is_null($this->test)){
            $this->retrieveAssociation("test");
        }
        return  $this->test;
    }
    /**
     *
     * @param Association $value
     */
    public function setTest($value) {
        $this->test = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationTest() {
        $this->retrieveAssociation("test");
    }

    

}
// end - wizard

?>
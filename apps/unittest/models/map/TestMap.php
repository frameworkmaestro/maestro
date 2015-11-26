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

class TestMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'unittest',
            'table' => 'Test',
            'attributes' => array(
                'idTest' => array('column' => 'idTest','key' => 'primary','idgenerator' => 'seq_test','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'result' => array('column' => 'result','type' => 'string'),
                'idObject' => array('column' => 'idObject','type' => 'string'),
                'idMethod' => array('column' => 'idMethod','key' => 'foreign','type' => 'integer'),
            ),
            'associations' => array(
                'parameters' => array('toClass' => '\unittest\models\Parameter', 'cardinality' => 'oneToMany' , 'keys' => 'idTest:idTest'), 
                'method' => array('toClass' => '\unittest\models\Method', 'cardinality' => 'oneToOne' , 'keys' => 'idMethod:idMethod'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idTest;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     *
     * @var string
     */
    protected $result;
    /**
     * 
     * @var string 
     */
    protected $idObject;
    /**
     * 
     * @var integer 
     */
    protected $idMethod;

    /**
     * Associations
     */
    protected $parameters;
    protected $method;
    

    /**
     * Getters/Setters
     */
    public function getIdTest() {
        return $this->idTest;
    }

    public function setIdTest($value) {
        $this->idTest = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getResult() {
        return $this->result;
    }

    public function setResult($value) {
        $this->result = $value;
    }

    public function getIdObject() {
        return $this->idObject;
    }

    public function setIdObject($value) {
        $this->idObject = $value;
    }

    public function getIdMethod() {
        return $this->idMethod;
    }

    public function setIdMethod($value) {
        $this->idMethod = $value;
    }
    /**
     *
     * @return Association
     */
    public function getParameters() {
        if (is_null($this->parameters)){
            $this->retrieveAssociation("parameters");
        }
        return  $this->parameters;
    }
    /**
     *
     * @param Association $value
     */
    public function setParameters($value) {
        $this->parameters = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationParameters() {
        $this->retrieveAssociation("parameters");
    }
    /**
     *
     * @return Association
     */
    public function getMethod() {
        if (is_null($this->method)){
            $this->retrieveAssociation("method");
        }
        return  $this->method;
    }
    /**
     *
     * @param Association $value
     */
    public function setMethod($value) {
        $this->method = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationMethod() {
        $this->retrieveAssociation("method");
    }

    

}
// end - wizard

?>
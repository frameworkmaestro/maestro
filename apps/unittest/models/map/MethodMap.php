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

class MethodMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'unittest',
            'table' => 'Method',
            'attributes' => array(
                'idMethod' => array('column' => 'idMethod','key' => 'primary','idgenerator' => 'seq_method','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'idModel' => array('column' => 'idModel','key' => 'foreign','type' => 'integer'),
            ),
            'associations' => array(
                'model' => array('toClass' => '\unittest\models\Model', 'cardinality' => 'oneToOne' , 'keys' => 'idModel:idModel'), 
                'tests' => array('toClass' => '\unittest\models\Test', 'cardinality' => 'oneToMany' , 'keys' => 'idMethod:idMethod'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idMethod;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     * 
     * @var integer 
     */
    protected $idModel;

    /**
     * Associations
     */
    protected $model;
    protected $tests;
    

    /**
     * Getters/Setters
     */
    public function getIdMethod() {
        return $this->idMethod;
    }

    public function setIdMethod($value) {
        $this->idMethod = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getIdModel() {
        return $this->idModel;
    }

    public function setIdModel($value) {
        $this->idModel = $value;
    }
    /**
     *
     * @return Association
     */
    public function getModel() {
        if (is_null($this->model)){
            $this->retrieveAssociation("model");
        }
        return  $this->model;
    }
    /**
     *
     * @param Association $value
     */
    public function setModel($value) {
        $this->model = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationModel() {
        $this->retrieveAssociation("model");
    }
    /**
     *
     * @return Association
     */
    public function getTests() {
        if (is_null($this->tests)){
            $this->retrieveAssociation("tests");
        }
        return  $this->tests;
    }
    /**
     *
     * @param Association $value
     */
    public function setTests($value) {
        $this->tests = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationTests() {
        $this->retrieveAssociation("tests");
    }

    

}
// end - wizard

?>
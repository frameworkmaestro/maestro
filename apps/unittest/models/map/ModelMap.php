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

class ModelMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'unittest',
            'table' => 'Model',
            'attributes' => array(
                'idModel' => array('column' => 'idModel','key' => 'primary','idgenerator' => 'seq_model','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'module' => array('column' => 'module','type' => 'string'),
                'idApp' => array('column' => 'idApp','key' => 'foreign','type' => 'integer'),
            ),
            'associations' => array(
                'methods' => array('toClass' => '\unittest\models\Method', 'cardinality' => 'oneToMany' , 'keys' => 'idModel:idModel'), 
                'app' => array('toClass' => '\unittest\models\App', 'cardinality' => 'oneToOne' , 'keys' => 'idApp:idApp'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idModel;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     * 
     * @var string 
     */
    protected $module;
    /**
     * 
     * @var integer 
     */
    protected $idApp;

    /**
     * Associations
     */
    protected $methods;
    protected $app;
    

    /**
     * Getters/Setters
     */
    public function getIdModel() {
        return $this->idModel;
    }

    public function setIdModel($value) {
        $this->idModel = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getModule() {
        return $this->module;
    }

    public function setModule($value) {
        $this->module = $value;
    }

    public function getIdApp() {
        return $this->idApp;
    }

    public function setIdApp($value) {
        $this->idApp = $value;
    }
    /**
     *
     * @return Association
     */
    public function getMethods() {
        if (is_null($this->methods)){
            $this->retrieveAssociation("methods");
        }
        return  $this->methods;
    }
    /**
     *
     * @param Association $value
     */
    public function setMethods($value) {
        $this->methods = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationMethods() {
        $this->retrieveAssociation("methods");
    }
    /**
     *
     * @return Association
     */
    public function getApp() {
        if (is_null($this->app)){
            $this->retrieveAssociation("app");
        }
        return  $this->app;
    }
    /**
     *
     * @param Association $value
     */
    public function setApp($value) {
        $this->app = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationApp() {
        $this->retrieveAssociation("app");
    }

    

}
// end - wizard

?>
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

class AppMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'unittest',
            'table' => 'App',
            'attributes' => array(
                'idApp' => array('column' => 'idApp','key' => 'primary','idgenerator' => 'seq_app','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
            ),
            'associations' => array(
                'models' => array('toClass' => '\unittest\models\Model', 'cardinality' => 'oneToMany' , 'keys' => 'idApp:idApp'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idApp;
    /**
     * 
     * @var string 
     */
    protected $name;

    /**
     * Associations
     */
    protected $models;
    

    /**
     * Getters/Setters
     */
    public function getIdApp() {
        return $this->idApp;
    }

    public function setIdApp($value) {
        $this->idApp = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }
    /**
     *
     * @return Association
     */
    public function getModels() {
        if (is_null($this->models)){
            $this->retrieveAssociation("models");
        }
        return  $this->models;
    }
    /**
     *
     * @param Association $value
     */
    public function setModels($value) {
        $this->models = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationModels() {
        $this->retrieveAssociation("models");
    }

    

}
// end - wizard

?>
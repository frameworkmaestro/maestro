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

class WordFormMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'WordForm',
            'attributes' => array(
                'idWordForm' => array('column' => 'idWordForm','key' => 'primary','idgenerator' => 'identity','type' => 'integer'),
                'form' => array('column' => 'form','type' => 'string'),
                'timeline' => array('column' => 'timeline','type' => 'string'),
                'idLexeme' => array('column' => 'idLexeme','type' => 'integer'),
            ),
            'associations' => array(
                'lexeme' => array('toClass' => 'fnbr20\models\Lexeme', 'cardinality' => 'oneToOne' , 'keys' => 'idLexeme:idLexeme'), 
                'timelines' => array('toClass' => 'fnbr20\models\Timeline', 'cardinality' => 'oneToMany' , 'keys' => 'timeline:timeline'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idWordForm;
    /**
     * 
     * @var string 
     */
    protected $form;
    /**
     * 
     * @var string 
     */
    protected $timeline;
    /**
     * 
     * @var integer 
     */
    protected $idLexeme;
    /**
     * 
     * @var integer 
     */
    protected $idLanguage;

    /**
     * Associations
     */
    protected $lexeme;
    protected $language;
    protected $timelines;
    

    /**
     * Getters/Setters
     */
    public function getIdWordForm() {
        return $this->idWordForm;
    }

    public function setIdWordForm($value) {
        $this->idWordForm = $value;
    }

    public function getForm() {
        return $this->form;
    }

    public function setForm($value) {
        $this->form = $value;
    }

    public function getTimeline() {
        return $this->timeline;
    }

    public function setTimeline($value) {
        $this->timeline = $value;
    }

    public function getIdLexeme() {
        return $this->idLexeme;
    }

    public function setIdLexeme($value) {
        $this->idLexeme = $value;
    }
    /**
     *
     * @return Association
     */
    public function getLexeme() {
        if (is_null($this->lexeme)){
            $this->retrieveAssociation("lexeme");
        }
        return  $this->lexeme;
    }
    /**
     *
     * @param Association $value
     */
    public function setLexeme($value) {
        $this->lexeme = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationLexeme() {
        $this->retrieveAssociation("lexeme");
    }
    /**
     *
     * @return Association
     */
    public function getTimelines() {
        if (is_null($this->timelines)){
            $this->retrieveAssociation("timelines");
        }
        return  $this->timelines;
    }
    /**
     *
     * @param Association $value
     */
    public function setTimelines($value) {
        $this->timelines = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationTimelines() {
        $this->retrieveAssociation("timelines");
    }

    

}
// end - wizard

?>
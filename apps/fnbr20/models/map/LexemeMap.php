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

class LexemeMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'Lexeme',
            'attributes' => array(
                'idLexeme' => array('column' => 'idLexeme','key' => 'primary','idgenerator' => 'identity','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'timeline' => array('column' => 'timeline','type' => 'string'),
                'idPOS' => array('column' => 'idPOS','type' => 'integer'),
                'idLanguage' => array('column' => 'idLanguage','type' => 'integer'),
            ),
            'associations' => array(
                'pos' => array('toClass' => 'fnbr20\models\POS', 'cardinality' => 'oneToOne' , 'keys' => 'idPOS:idPOS'), 
                'wordforms' => array('toClass' => 'fnbr20\models\WordForm', 'cardinality' => 'oneToMany' , 'keys' => 'idLexeme:idLexeme'), 
                'lexemeentries' => array('toClass' => 'fnbr20\models\LexemeEntry', 'cardinality' => 'oneToMany' , 'keys' => 'idLexeme:idLexeme'), 
                'language' => array('toClass' => 'fnbr20\models\Language', 'cardinality' => 'oneToOne' , 'keys' => 'idLanguage:idLanguage'), 
                'timelines' => array('toClass' => 'fnbr20\models\Timeline', 'cardinality' => 'oneToMany' , 'keys' => 'timeline:timeline'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idLexeme;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     * 
     * @var string 
     */
    protected $timeline;
    /**
     * 
     * @var integer 
     */
    protected $idPOS;
    /**
     * 
     * @var integer 
     */
    protected $idLanguage;

    /**
     * Associations
     */
    protected $pos;
    protected $wordforms;
    protected $language;
    protected $lexemeentries;
    protected $timelines;
    

    /**
     * Getters/Setters
     */
    public function getIdLexeme() {
        return $this->idLexeme;
    }

    public function setIdLexeme($value) {
        $this->idLexeme = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getTimeline() {
        return $this->timeline;
    }

    public function setTimeline($value) {
        $this->timeline = $value;
    }

    public function getIdPOS() {
        return $this->idPOS;
    }

    public function setIdPOS($value) {
        $this->idPOS = $value;
    }
    public function getIdLanguage() {
        return $this->idLanguage;
    }

    public function setIdLanguage($value) {
        $this->idLanguage = $value;
    }
    /**
     *
     * @return Association
     */
    public function getPos() {
        if (is_null($this->pos)){
            $this->retrieveAssociation("pos");
        }
        return  $this->pos;
    }
    /**
     *
     * @param Association $value
     */
    public function setPos($value) {
        $this->pos = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationPos() {
        $this->retrieveAssociation("pos");
    }
    /**
     *
     * @return Association
     */
    public function getWordforms() {
        if (is_null($this->wordforms)){
            $this->retrieveAssociation("wordforms");
        }
        return  $this->wordforms;
    }
    /**
     *
     * @param Association $value
     */
    public function setWordforms($value) {
        $this->wordforms = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationWordforms() {
        $this->retrieveAssociation("wordforms");
    }
    /**
     *
     * @return Association
     */
    public function getLexemeentries() {
        if (is_null($this->lexemeentries)){
            $this->retrieveAssociation("lexemeentries");
        }
        return  $this->lexemeentries;
    }
    /**
     *
     * @param Association $value
     */
    public function setLexemeentries($value) {
        $this->lexemeentries = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationLexemeentries() {
        $this->retrieveAssociation("lexemeentries");
    }
    /**
     *
     * @return Association
     */
    public function getLanguage() {
        if (is_null($this->language)){
            $this->retrieveAssociation("language");
        }
        return  $this->language;
    }
    /**
     *
     * @param Association $value
     */
    public function setLanguage($value) {
        $this->language = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationLanguage() {
        $this->retrieveAssociation("language");
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
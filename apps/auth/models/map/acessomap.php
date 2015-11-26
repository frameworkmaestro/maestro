<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage vendas0
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

// wizard - code section created by Wizard Module - 27/01/2012 01:32:09

namespace auth\models\map;

class AcessoMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idAcesso;
    /**
     * 
     * @var integer 
     */
    protected $idTransacao;
    /**
     * 
     * @var integer 
     */
    protected $idGrupo;
    /**
     * 
     * @var integer 
     */
    protected $direito;

    /**
     * Associations
     */
    protected $transacao;
    protected $grupo;
    

    /**
     * Getters/Setters
     */
    public function getIdAcesso() {
        return $this->idAcesso;
    }

    public function setIdAcesso($value) {
        $this->idAcesso = ($value ? : NULL);
    }

    public function getIdTransacao() {
        return $this->idTransacao;
    }

    public function setIdTransacao($value) {
        $this->idTransacao = $value;
    }

    public function getIdGrupo() {
        return $this->idGrupo;
    }

    public function setIdGrupo($value) {
        $this->idGrupo = $value;
    }

    public function getDireito() {
        return $this->direito;
    }

    public function setDireito($value) {
        $this->direito = $value;
    }
    /**
     *
     * @return Association
     */
    public function getTransacao() {
        if (is_null($this->transacao)){
            $this->retrieveAssociation("transacao");
        }
        return  $this->transacao;
    }
    /**
     *
     * @param Association $value
     */
    public function setTransacao($value) {
        $this->transacao = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationTransacao() {
        $this->retrieveAssociation("transacao");
    }
    /**
     *
     * @return Association
     */
    public function getGrupo() {
        if (is_null($this->grupo)){
            $this->retrieveAssociation("grupo");
        }
        return  $this->grupo;
    }
    /**
     *
     * @param Association $value
     */
    public function setGrupo($value) {
        $this->grupo = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationGrupo() {
        $this->retrieveAssociation("grupo");
    }

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'dlivro',
            'table' => 'Acesso',
            'attributes' => array(
                'idAcesso' => array('column' => 'idAcesso','key' => 'primary','idgenerator' => 'seq_acesso','type' => 'integer'),
                'idTransacao' => array('column' => 'idTransacao','key' => 'foreign','type' => 'integer'),
                'idGrupo' => array('column' => 'idGrupo','key' => 'foreign','type' => 'integer'),
                'direito' => array('column' => 'direito','type' => 'integer'),
            ),
            'associations' => array(
                'transacao' => array('toClass' => '\auth\models\transacao', 'cardinality' => 'oneToOne' , 'keys' => 'idTransacao:idTransacao'), 
                'grupo' => array('toClass' => '\auth\models\grupo', 'cardinality' => 'oneToOne' , 'keys' => 'idGrupo:idGrupo'), 
            )
        );
    }

}
// end - wizard

?>
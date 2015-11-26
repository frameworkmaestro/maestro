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

class LogMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idLog;
    /**
     * 
     * @var integer 
     */
    protected $idUsuario;
    /**
     * 
     * @var timestamp 
     */
    protected $timestamp;
    /**
     * 
     * @var string 
     */
    protected $descricao;
    /**
     * 
     * @var string 
     */
    protected $operacao;
    /**
     * 
     * @var integer 
     */
    protected $idModel;

    /**
     * Associations
     */
    protected $usuario;
    

    /**
     * Getters/Setters
     */
    public function getIdLog() {
        return $this->idLog;
    }

    public function setIdLog($value) {
        $this->idLog = ($value ? : NULL);
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($value) {
        $this->idUsuario = $value;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($value) {
        if (!($value instanceof \MTimeStamp)) {
            $value = new \MTimeStamp($value);
        }
        $this->timestamp = $value;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($value) {
        $this->descricao = $value;
    }

    public function getOperacao() {
        return $this->operacao;
    }

    public function setOperacao($value) {
        $this->operacao = $value;
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
    public function getUsuario() {
        if (is_null($this->usuario)){
            $this->retrieveAssociation("usuario");
        }
        return  $this->usuario;
    }
    /**
     *
     * @param Association $value
     */
    public function setUsuario($value) {
        $this->usuario = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationUsuario() {
        $this->retrieveAssociation("usuario");
    }

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'dlivro',
            'table' => 'Log',
            'attributes' => array(
                'idLog' => array('column' => 'idLog','key' => 'primary','idgenerator' => 'seq_log','type' => 'integer'),
                'idUsuario' => array('column' => 'idUsuario','key' => 'foreign','type' => 'integer'),
                'timestamp' => array('column' => 'timestamp','type' => 'timestamp'),
                'descricao' => array('column' => 'descricao','type' => 'string'),
                'operacao' => array('column' => 'operacao','type' => 'string'),
                'idModel' => array('column' => 'idModel','type' => 'integer'),
            ),
            'associations' => array(
                'usuario' => array('toClass' => '\auth\models\usuario', 'cardinality' => 'oneToOne' , 'keys' => 'idUsuario:idUsuario'), 
            )
        );
    }

}
// end - wizard

?>
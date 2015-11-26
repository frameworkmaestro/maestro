<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage exemplos
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

// wizard - code section created by Wizard Module

namespace exemplos\models\map;

class UsuarioMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idUsuario;
    /**
     * 
     * @var integer 
     */
    protected $idPessoa;
    /**
     * 
     * @var integer 
     */
    protected $idSetor;
    /**
     * 
     * @var string 
     */
    protected $login;
    /**
     * 
     * @var string 
     */
    protected $password;
    /**
     * 
     * @var string 
     */
    protected $passMD5;

    /**
     * Associations
     */
    protected $pessoa;
    protected $setor;
    protected $grupos;
    

    /**
     * Getters/Setters
     */
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($value) {
        $this->idUsuario = ($value ? : NULL);
    }

    public function getIdPessoa() {
        return $this->idPessoa;
    }

    public function setIdPessoa($value) {
        $this->idPessoa = $value;
    }

    public function getIdSetor() {
        return $this->idSetor;
    }

    public function setIdSetor($value) {
        $this->idSetor = $value;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($value) {
        $this->login = $value;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($value) {
        $this->password = $value;
    }

    public function getPassMD5() {
        return $this->passMD5;
    }

    public function setPassMD5($value) {
        $this->passMD5 = $value;
    }
    /**
     *
     * @return Association
     */
    public function getPessoa() {
        if (is_null($this->pessoa)){
            $this->retrieveAssociation("pessoa");
        }
        return  $this->pessoa;
    }
    /**
     *
     * @param Association $value
     */
    public function setPessoa($value) {
        $this->pessoa = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationPessoa() {
        $this->retrieveAssociation("pessoa");
    }
    /**
     *
     * @return Association
     */
    public function getSetor() {
        if (is_null($this->setor)){
            $this->retrieveAssociation("setor");
        }
        return  $this->setor;
    }
    /**
     *
     * @param Association $value
     */
    public function setSetor($value) {
        $this->setor = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationSetor() {
        $this->retrieveAssociation("setor");
    }
    /**
     *
     * @return Association
     */
    public function getGrupos() {
        if (is_null($this->grupos)){
            $this->retrieveAssociation("grupos");
        }
        return  $this->grupos;
    }
    /**
     *
     * @param Association $value
     */
    public function setGrupos($value) {
        $this->grupos = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationGrupos() {
        $this->retrieveAssociation("grupos");
    }

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'exemplos',
            'table' => 'Usuario',
            'attributes' => array(
                'idUsuario' => array('column' => 'idUsuario','key' => 'primary','idgenerator' => 'seq_usuario','type' => 'integer'),
                'idPessoa' => array('column' => 'idPessoa','key' => 'foreign','type' => 'integer'),
                'idSetor' => array('column' => 'idSetor','key' => 'foreign','type' => 'integer'),
                'login' => array('column' => 'login','type' => 'string'),
                'password' => array('column' => 'password','type' => 'string'),
                'passMD5' => array('column' => 'passMD5','type' => 'string'),
            ),
            'associations' => array(
                'pessoa' => array('toClass' => '\exemplos\models\pessoa', 'cardinality' => 'oneToOne' , 'keys' => 'idPessoa:idPessoa'), 
                'setor' => array('toClass' => '\exemplos\models\setor', 'cardinality' => 'oneToOne' , 'keys' => 'idSetor:idSetor'), 
                'grupos' => array('toClass' => '\exemplos\models\grupo', 'cardinality' => 'manyToMany' , 'associative' => 'usuario_grupo'), 
            )
        );
    }

}
// end - wizard

?>
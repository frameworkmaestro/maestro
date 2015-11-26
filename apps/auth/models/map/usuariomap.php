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

class UsuarioMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idUsuario;
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

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($value) {
        $this->login = ($value ? : NULL);
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($value) {
        $this->password = ($value ? : NULL);
    }

    public function getPassMD5() {
        return $this->passMD5;
    }

    public function setPassMD5($value) {
        $this->passMD5 = ($value ? : NULL);
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
            'database' => 'dlivro',
            'table' => 'Usuario',
            'attributes' => array(
                'idUsuario' => array('column' => 'idUsuario','key' => 'primary','idgenerator' => 'seq_usuario','type' => 'integer'),
                'login' => array('column' => 'login','type' => 'string'),
                'password' => array('column' => 'password','type' => 'string'),
                'passMD5' => array('column' => 'passMD5','type' => 'string'),
            ),
            'associations' => array(
                'grupos' => array('toClass' => '\auth\models\grupo', 'cardinality' => 'manyToMany' , 'associative' => 'usuario_grupo'), 
            )
        );
    }

}
// end - wizard

?>
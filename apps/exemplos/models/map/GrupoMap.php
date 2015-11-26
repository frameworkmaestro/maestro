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

class GrupoMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idGrupo;
    /**
     * 
     * @var string 
     */
    protected $grupo;

    /**
     * Associations
     */
    protected $acessos;
    protected $usuarios;
    

    /**
     * Getters/Setters
     */
    public function getIdGrupo() {
        return $this->idGrupo;
    }

    public function setIdGrupo($value) {
        $this->idGrupo = ($value ? : NULL);
    }

    public function getGrupo() {
        return $this->grupo;
    }

    public function setGrupo($value) {
        $this->grupo = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAcessos() {
        if (is_null($this->acessos)){
            $this->retrieveAssociation("acessos");
        }
        return  $this->acessos;
    }
    /**
     *
     * @param Association $value
     */
    public function setAcessos($value) {
        $this->acessos = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationAcessos() {
        $this->retrieveAssociation("acessos");
    }
    /**
     *
     * @return Association
     */
    public function getUsuarios() {
        if (is_null($this->usuarios)){
            $this->retrieveAssociation("usuarios");
        }
        return  $this->usuarios;
    }
    /**
     *
     * @param Association $value
     */
    public function setUsuarios($value) {
        $this->usuarios = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationUsuarios() {
        $this->retrieveAssociation("usuarios");
    }

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'exemplos',
            'table' => 'Grupo',
            'attributes' => array(
                'idGrupo' => array('column' => 'idGrupo','key' => 'primary','idgenerator' => 'seq_grupo','type' => 'integer'),
                'grupo' => array('column' => 'grupo','type' => 'string'),
            ),
            'associations' => array(
                'acessos' => array('toClass' => '\exemplos\models\acesso', 'cardinality' => 'oneToMany' , 'keys' => 'idGrupo:idGrupo'), 
                'usuarios' => array('toClass' => '\exemplos\models\usuario', 'cardinality' => 'manyToMany' , 'associative' => 'usuario_grupo'), 
            )
        );
    }

}
// end - wizard

?>
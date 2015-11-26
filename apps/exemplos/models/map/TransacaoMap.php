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

class TransacaoMap extends \MBusinessModel {

    
    /**
     * 
     * @var integer 
     */
    protected $idTransacao;
    /**
     * 
     * @var string 
     */
    protected $transacao;
    /**
     * 
     * @var string 
     */
    protected $descricao;

    /**
     * Associations
     */
    protected $acessos;
    

    /**
     * Getters/Setters
     */
    public function getIdTransacao() {
        return $this->idTransacao;
    }

    public function setIdTransacao($value) {
        $this->idTransacao = ($value ? : NULL);
    }

    public function getTransacao() {
        return $this->transacao;
    }

    public function setTransacao($value) {
        $this->transacao = $value;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($value) {
        $this->descricao = $value;
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

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'exemplos',
            'table' => 'Transacao',
            'attributes' => array(
                'idTransacao' => array('column' => 'idTransacao','key' => 'primary','idgenerator' => 'seq_transacao','type' => 'integer'),
                'transacao' => array('column' => 'transacao','type' => 'string'),
                'descricao' => array('column' => 'descricao','type' => 'string'),
            ),
            'associations' => array(
                'acessos' => array('toClass' => '\exemplos\models\acesso', 'cardinality' => 'oneToMany' , 'keys' => 'idTransacao:idTransacao'), 
            )
        );
    }

}
// end - wizard

?>
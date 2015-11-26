<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage vendas0
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace auth\models;

class Usuario extends map\UsuarioMap {

    public static function config() {
        return array(
            'log' => array( 'login' ),
            'validators' => array(
                'login' => array('notnull'),
            ),
            'converters' => array()
        );
    }
    
    public function getDescription(){
        return $this->getLogin();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('login');
        if ($filter->login){
            $criteria->where("login LIKE '{$filter->login}%'");
        }
        return $criteria;
    }

    public function getProfile(){
        return $this->login;
    }

    public function getName(){
        return $this->login;
    }

    public function validatePasswordMD5($challenge, $response)
    {
        $hash_pass = MD5(trim($this->login) . ':' . trim($this->passMD5) . ":" . $challenge);
        return ($hash_pass == $response);
    }

    public function getArrayGroups()
    {
        $aGroups = array();
        $grupos = $this->getGrupos();
        foreach ($grupos as $grupo)
        {
            $g = $grupo->getGrupo();
            $aGroups[$g] = $g;
        }
        return $aGroups;
    }


    public function getRights()
    {
        $query = $this->getCriteria()->
                select('grupos.acessos.transacao.transacao', 'max(grupos.acessos.direito) as direito')->
                where("login = '{$this->login}'")->
                groupBy('grupos.acessos.transacao.transacao')->
                asQuery();
        return $query->chunkResult(0, 1, false);
    }


    public function weakPassword()
    {
        $weak = ($this->passMD5 == MD5('010101')) || ($this->passMD5 == MD5($this->login));
        return $weak;
    }

    public function getByLogin($login)
    {
        $criteria = $this->getCriteria()->
                where("login = '{$login}'");
        $this->retrieveFromCriteria($criteria);
        return $this;
    }
    
    public function listGrupos(){
        $criteria= $this->getCriteria()->select("grupos.idGrupo,grupos.grupo")->orderBy("grupos.grupo");
        if($this->idUsuario){
            $criteria->where("idUsuario = {$this->idUsuario}");
        }
        return $criteria;
    }
}

?>
<?php

/**
 * 
 *
 * @category   SIGA
 * @package    UFJF
 * @subpackage examples_Classes
 * @copyright  Copyright (c) 2003-2011 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace exemplos\models;

class Setor extends map\SetorMap {

    public static function config() {
        return array(
            'log' => array('nome'),
            'validators' => array(
                //'sigla' => array('notnull', 'notblank', 'minlength' => 3),
                //'nome' => array('notnull', 'notblank', 'minlength' => 10),
            ),
            'converters' => array(
            )
        );
    }

    public function getDescription() {
        return $this->getNome();
    }

    public function listByFilter($filter) {
        $criteria = $this->getCriteria()->
                        select('*');
        if ($filter->login) {
            $criteria->where('nome', 'LIKE', $filter->nome . "%");
        }
        return $criteria;
    }

    public function listAlocacao() {
        $criteria = $this->getCriteria()->
                        select('*', 'usuarios.idSetor')->distinct(true);
        return $criteria;
    }

    public function novo($nome) {
        // operação CRUD com transação própria
        $this->setPersistent(false);
        $this->setSigla(substr($nome,0,3));
        $this->setNome($nome);
        $this->save();
    }

    /**
     * ------------------ Associações
     */

    /**
     * Operações com associações.
     */
    public function associations() {
        // retrieve by Id
        $this->getById(1);
        foreach($this->getUsuarios() as $usuario){
            $nome = $usuario->getPessoa()->getNome();
        }
        // adiciona um usuário existente a um setor
        $usuario = new Usuario(1);
        $usuario->setIdPessoa(3);
        $usuario->setLogin('Novo Login');
        $usuario->setPassword('123456');
        $this->getUsuarios()->append($usuario);
        $this->saveAssociation('usuarios');

        // contagem de usuários em um setor
        $n = $this->getUsuarios()->count();

        // Salva associação com base no id dos usuários
        $idUsuario = array(1,3,5,6);
        $this->saveAssociation('usuarios', $idUsuario);
        mdump('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
        $this->deleteAssociationById('usuarios',$idUsuario );
        mdump('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
        
    }
    
    /**
     * Associação automatica na clausula WHERE
     */
    public function associationWhere(){
        $this->getById(1);
        $criteria = $this->getCriteria()->
                select('count(*)')->
                where("usuarios.idSetor = {$this->idSetor}");
        return $criteria;        
    } 

}

?>
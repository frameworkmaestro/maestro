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

namespace crud\models;

class Aluno extends map\AlunoMap {

    public static function config() {
        return array(
            'log' => array('matricula'),
            'validators' => array(
                'matricula' => array('notnull', 'notblank', 'minlength' => 8),
                'curso' => array('notnull'),
            ),
            'converters' => array(
            )
        );
    }

    public function getDescription() {
        return $this->getMatricula();
    }
    

    public function listByFilter($filter) {
        $criteria = $this::getCriteria()->select('idAluno, matricula, nome');
        if ($filter->nome) {
            $criteria->where("nome LIKE '%{$filter->nome}%'");
        }
        if ($filter->matricula) {
            $criteria->where("matricula LIKE '{$filter->matricula}%'");
        }
        return $criteria;
    }    

    /**
     * ------------------ Criteria Methods
     */

    /**
     * Query with automatic join and expressions.
     * @return <type>
     */
    public function criteriaMethod01() {
        $criteria = $this->getCriteria()->
                        select('*, nome');
        return $criteria;
    }

    /**
     * Query with superclass association.
     * @return <type>
     */
    public function criteriaMethod02() {
        $criteria = $this->getCriteria()->
                        select('matricula, nome, U.login')->
                        join('pessoa', 'usuario U', 'pessoa.idPessoa=U.idPessoa', 'left');
        return $criteria;
    }

    /**
     * Query with automatic superclass association.
     * @return <type>
     */
    public function criteriaMethod03() {
        $criteria = $this->getCriteria();
        return $criteria;
    }
    /**
     * ------------------ Criteria Commands
     */

    /**
     * Query with automatic join and expressions.
     * @return <type>
     */
    public function criteriaCommand01() {
        $criteria = $this->getCriteria("select *, nome");
        return $criteria;
    }

    /**
     * Query with superclass association.
     * @return <type>
     */
    public function criteriaCommand02() {
        $criteria = $this->getCriteria(
                        "select matricula, nome, U.login " .
                        "from pessoa left join usuario U on (pessoa.idPessoa = U.idPessoa)"
        );
        return $criteria;
    }

    /**
     * ------------------ Herança
     */

    public function heranca(){
        // model Aluno herda de model Pessoa
        // define atributos para ambas as classes simultaneamente
        $data = new \stdClass();
        $data->matricula = '2011111234';
        $data->curso = 'Computação';
        $data->nome = 'Novo Aluno - 2011111234';
        $data->cpf = '12345678901';
        $data->dataNascimento = '12/04/1987';
        // se idPessoa não estiver definido, cria um novo objeto Pessoa, associado ao Aluno
        // se idPessoa estiver definido, cria apenas o objeto Aluno
        //$data->idPessoa = 3;
        $this->setData($data);
        $this->save();
    }

}

?>
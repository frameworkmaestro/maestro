<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage 
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace ddd\persistence\maestro\repository;

//class UserReadRepository extends \carteirinha\models\map\CarteirinhaMap implements ReadRepository
use \ddd\models\repository\UserReadRepositoryInterface;

class UserReadRepository extends BaseReadRepository implements UserReadRepositoryInterface
{

    public function listByFilter($user, $filter) {
        $criteria = $this->pm->getRetrieveCriteria($user)
            ->select('*');
        return $criteria;
    }

    public function getDescription(){
        return $this->getId();
    }
    /*
    public function getNome($idCarteira) {
        return $this->getById($idCarteira)->getPessoa()->getNome();
    }
    
    public function listCarteirinhaFuncionario($filter){

        $criteria = $this->getCriteria()
            ->select("*, funcionario.pessoa.nome, funcionario.pessoa.idPessoa, funcionario.pessoa.CPF, rh_Vinculo.idVinculo")
            ->orderBy('funcionario.pessoa.nome');
        $criteria->where("((funcionario.vinculos.idVinculo is not null) and ((tipo <> '1') OR tipo is null))");
        $criteria->setAssociationType('funcionario.vinculos', 'left');
        $criteria->setAssociationType('funcionario', 'right');
        $criteria->where('funcionario.vinculos.dataVacancia is null');
        if ($filter->idCarteira){
            $criteria->where("idCarteira = {$filter->idCarteira}");
        }
        if ($filter->cpf){
            $criteria->where("funcionario.pessoa.cpf = '{$filter->cpf}'");
        }
        if ($filter->idPessoa){
            $criteria->where("funcionario.pessoa.idPessoa = {$filter->idPessoa}");
        }
        return $criteria;
    }

    public function listCarteirinhaEstudante($idPessoa){
        $criteria = $this->getCriteria()
            ->select("*, aluno.pessoa.nome, aluno.pessoa.idPessoa, aluno.programas.matricula, aluno.pessoa.CPF, aluno.programas.aCurriculo.aCurso.nome as curso")
            ->orderBy('aluno.pessoa.nome');
        $criteria->setAssociationType('aluno.programas', 'left');
        $criteria->setAssociationType('aluno', 'right');
        $criteria->where('aluno.programas.idSituacao = 2');
        $criteria->where("((aluno.programas.idPrograma is not null) and ((tipo = '1') OR tipo is null))");
        $criteria->where("aluno.pessoa.idPessoa = {$idPessoa->idPessoa}");
        return $criteria;
    }
    */
}

<?php

use exemplos\models as models;

class PersistenceController extends MController {

    public function main() {
        $this->render();
    }

    public function criteriaMethods() {
        $filter = new StdClass();
        $filter->login = 'admin';
        
        // Método Estático (sem instanciar o Modelo)
        $result = models\Usuario::staticListAll()->asQuery()->getResult();

        $usuario = new models\Usuario();
        // Join automatico e expressoes
        $result = $usuario->criteriaMethod01()->asQuery()->getResult();
        // Join automatico e parametro
        $result = $usuario->criteriaMethod02($filter)->asQuery()->getResult();
        // Join forçado
        $result = $usuario->criteriaMethod03($filter)->asQuery()->getResult();
        // Alias com join automatico
        $result = $usuario->criteriaMethod04($filter)->asQuery()->getResult();
        // Alias com join forçado
        $result = $usuario->criteriaMethod05($filter)->asQuery()->getResult();
        // Parâmetros
        $result = $usuario->criteriaMethod06($filter)->asQuery('admin','F%')->getResult();
        $result = $usuario->criteriaMethod06($filter)->asQuery(array('admin','F%'))->getResult();
        // Clásula distinct
        $result = $usuario->criteriaMethod07()->asQuery()->getResult();
        // Associação Many-to-many
        $result = $usuario->criteriaMethod08()->asQuery()->getResult();
        // Group By e Agregações
        $result = $usuario->criteriaMethod09()->asQuery()->getResult();
        // Operador IN
        $result = $usuario->criteriaMethod10()->asQuery()->getResult();
        // Auto-associação
        $result = $usuario->criteriaMethod11()->asQuery()->getResult();
        // Subqueries e parâmetros
        $result = $usuario->criteriaMethod12()->asQuery()->getResult();
        // Subqueries com referência a query externa
        $result = $usuario->criteriaMethod13()->asQuery()->getResult();
        // Outer join
        $result = $usuario->criteriaMethod14()->asQuery()->getResult();
        // herança
        $aluno = new models\Aluno();
        $result = $aluno->criteriaMethod01()->asQuery()->getResult();
        $result = $aluno->criteriaMethod02()->asQuery()->getResult();
        $result = $aluno->criteriaMethod03()->asQuery()->getResult();
        // by-pass a SuperClasse
        $result = $usuario->criteriaMethod15()->asQuery()->getResult();
        // Condição complexa
        $result = $usuario->criteriaMethod16()->asQuery()->getResult();
        // Parâmetros nomeados (com array)
        $result = $usuario->criteriaMethod17($filter)->asQuery()->getResult();
        // Parâmetros nomeados (com objetos)
        $result = $usuario->criteriaMethod18($filter)->asQuery()->getResult();
        // Table criteria - criteria usado na clausla FROM
        $result = $usuario->criteriaMethod19($filter)->asQuery()->getResult();

        $this->render();
    }

    public function criteriaCommands() {
        $filter = $this->data;
        $filter->login = 'admin';
        $usuario = new models\Usuario();
        // Join automatico e expressoes
        $criteria = $usuario->criteriaCommand01()->asQuery()->getResult();
        // Join automatico e parametro
        $criteria = $usuario->criteriaCommand02($filter)->asQuery()->getResult();
        // Join forçado
        $criteria = $usuario->criteriaCommand03($filter)->asQuery()->getResult();
        // Alias com join automatico
        $criteria = $usuario->criteriaCommand04($filter)->asQuery()->getResult();
        // Alias com join forçado
        $criteria = $usuario->criteriaCommand05($filter)->asQuery()->getResult();
        // Parâmetros
        $criteria = $usuario->criteriaCommand06($filter)->asQuery('admin','F%')->getResult();
        $criteria = $usuario->criteriaCommand06($filter)->asQuery(array('admin','F%'))->getResult();
        // Clásula distinct
        $criteria = $usuario->criteriaCommand07()->asQuery()->getResult();
        // Associação Many-to-many
        $criteria = $usuario->criteriaCommand08()->asQuery()->getResult();
        // Group By e Agregações
        $criteria = $usuario->criteriaCommand09()->asQuery()->getResult();
        // Operador IN
        $criteria = $usuario->criteriaCommand10()->asQuery()->getResult();
        // Auto-associação
        $criteria = $usuario->criteriaCommand11()->asQuery()->getResult();
        // Subqueries e parâmetros
        $criteria = $usuario->criteriaCommand12()->asQuery()->getResult();
        // Subqueries com referência a query externa
        //$criteria = $usuario->criteriaCommand13()->asQuery()->getResult();
        // outer join
        $criteria = $usuario->criteriaCommand14()->asQuery()->getResult();
        // herança
        $aluno = new models\Aluno();
        $criteria = $aluno->criteriaCommand01()->asQuery()->getResult();
        $criteria = $aluno->criteriaCommand02()->asQuery()->getResult();
        // by-pass a SuperClasse
        $criteria = $usuario->criteriaCommand15()->asQuery()->getResult();
        // Condição complexa
        $criteria = $usuario->criteriaCommand16()->asQuery()->getResult();

        $this->render();
    }
    
    public function objectMethods() {
        $filter = $this->data;
        $usuario = new models\Usuario();
        // métodos CRUD
        $usuario->methods();
        $aluno = new models\Aluno();
        // persitencia com herança
        $aluno->heranca();
        $this->render();
    }

    public function objectAssociations() {
        $filter = $this->data;
        $setor = new models\Setor();
        // uso de associações
        $setor->associations();
        // uso de associação na cláusula WHERE
        $criteria = $setor->associationWhere()->asQuery()->getResult();
        // obter o objeto associado: oneToMany retorna objeto Association
        $setor->getById(1);
        $setor->getUsuarios();
        // obter o objeto associado: oneToOne retorna objeto associado
        $usuario = new models\Usuario(1);
        $usuario->getPessoa();
        // associação com outro objeto - 1:1
        $usuario->saveAssociationById('setor',1);
        // associação com outros objetos - N:N
        $grupos = array(1,2,3);
        mdump('$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$');
        $usuario->saveAssociationById('grupos',$grupos);
        mdump('$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$');
        // associação com outro objeto - 1:N
        $setor = new models\Setor(1);
        $usuarios = array(1,2,3);
        $setor->saveAssociationById('usuarios', $usuarios);
        $this->render();
    }

    public function objectTransactions() {
        $filter = $this->data;
        $filter->login = 'admin';
        $usuario = new models\Usuario();
        // transações automáticas
        $usuario->automaticTransaction();
        // duas operações independentes
        $usuario->twoOperations();
        // duas operações dependentes
        $usuario->forcedTransaction();
        // operações em dois objetos distintos
        $usuario->twoObjects();
        // operações em dois objetos distintos com transações aninhadas
        $usuario->nestedTransactions();
        
        $this->render();
    }  
    
    public function setOperations(){
        // Operações de conjunto
        $usuario = new models\Usuario();
        // UNION
        $usuario->unionOperation()->asQuery()->getResult();
        // INTERSECT
        $usuario->intersectOperation()->asQuery()->getResult();
        // DIFFERENCE
        $usuario->differenceOperation()->asQuery()->getResult();
        
        $this->render();
    }

    public function sql() {
        // consultas usando SQL
        $usuario = new models\Usuario();
        // consulta usando select
        $usuario->query();
        // comandos DML
        $usuario->command();
        $this->render();
    }

}
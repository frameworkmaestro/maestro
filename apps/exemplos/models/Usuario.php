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

class Usuario extends map\UsuarioMap {

    public static function config() {
        return array(
            'log' => array('login'),
            'validators' => array(
                'login' => array('notnull', 'notblank', 'minlength' => 8, 'maxlength' => 11),
                'password' => array('notnull'),
            ),
            'converters' => array(
            )
        );
    }

    public function getDescription() {
        return $this->getLogin();
    }

    public function listByFilter($filter) {
        $criteria = $this->getCriteria()->
                        select('*', 'pessoa.nome')->orderBy('login');
        if ($filter->login) {
            $criteria->where("login LIKE '{$filter->login}%'");
        }
        if ($filter->nome) {
            $criteria->where('pessoa.nome', 'LIKE', "'{$filter->nome}%'");
        }
        if ($filter->idUsuario) {
            $criteria->where('idUsuario', '=', "'{$filter->idUsuario}'");
        }
        return $criteria;
    }

    public function listBySetor($idSetor) {
        $criteria = $this->getCriteria()->
                        select('*')->orderBy('login');
        $criteria->where("idSetor = {$idSetor}");
        return $criteria;
    }

    public function listExemploCSV() {
        $criteria = $this->getCriteria()->
                        select('idUsuario, login, idSetor, pessoa.nome, pessoa.cpf, pessoa.dataNascimento, pessoa.email')->
                        orderBy('pessoa.nome');
        return $criteria;
    }

    /**
     * ------------------ Consultas usando SQL
     */

    /**
     * Query com parametros
     * @return <type>
     */
    public function query() {
        // com passagem de parâmetros ($parameters, $page, $rows)
        $result = $this->getDb()->executeQuery("select idUsuario from usuario where idUsuario > ?", 100, 3, 5);
        // usando encadeamento
        $query = $this->getDb()->getQueryCommand("select idUsuario from usuario where idUsuario > ?")->setParameters(100)->setRange(3, 5);
        $result = $query->getResult();
    }

    /**
     * Execução de comandos (insert, update, delete)
     * @return <type>
     */
    public function command() {
        try {
            // iniciar transação
            $transaction = $this->beginTransaction();
            // obter id
            $idUsuario = $this->getNewId('seq_usuario');
            // inserção
            $this->getDb()->executeCommand('insert into usuario (idUsuario, login, idPessoa) values (?, ?, ?)', array($idUsuario, 'exemplo',4));
            // atualização
            $this->getDb()->executeCommand('update usuario set login = ? where idUsuario = ?', array('Exemplo Atualizado', $idUsuario));
            // remoção
            $this->getDb()->executeCommand('delete from usuario where idUsuario = ?', $idUsuario);
            // commit da transação
            $transaction->commit();
        } catch (\EModelException $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
    }

    /**
     * Static criteria
     * @return <criteria>
     */
    
    public static function staticListAll(){
        $criteria = \PersistentManager::getCriteria('exemplos\models\Usuario');
        $criteria->select('*');
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
                        select("*, (pessoa.nome || ' ' || pessoa.email) as nome")->
                        where('pessoa.idPessoa = 1')->
                        and_("pessoa.dataNascimento",">", \Manager::date('01/01/1976'))->
                        orderBy('pessoa.nome');
        return $criteria;
    }

    /**
     * Query with automatic join and test parameter.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod02($filter) {
        $criteria = $this->getCriteria()->
                        select('idUsuario, login, pessoa.nome, pessoa.cpf')->
                        where('login', '=', "'{$filter->login}'");
        $criteria->setAssociationAlias('grupos.acessos','a');                
        return $criteria;
    }

    /**
     * Query with forced join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod03($filter) {
        $criteria = $this->getCriteria()->
                        select('idUsuario, login')->
                        join('usuario', 'pessoa', 'usuario.idPessoa=pessoa.idPessoa')->
                        where('login', '=', '?')->
                        parameters($filter->login);
        return $criteria;
    }

    /**
     * Query with alias and automatic join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod04($filter) {
        $criteria = $this->getCriteria()->
                        select('U.idUsuario, P.nome')->
                        where('U.login', '=', "'{$filter->login}'")->
                        setAlias('U')->
                        setAssociationAlias('pessoa', 'P');
        return $criteria;
    }

    /**
     * Query with alias and forced join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod05($filter) {
        $criteria = $this->getCriteria()->
                        select('U.idUsuario, P.nome')->
                        join('usuario U', 'pessoa P', 'U.idPessoa = P.idPessoa')->
                        where('U.login', '=', "'{$filter->login}'");
        return $criteria;
    }

    /**
     * Query using parameters
     * @return <type>
     */
    public function criteriaMethod06() {
        $criteria = $this->getCriteria()->
                        select('U.idUsuario, P.nome')->
                        join('usuario U', 'pessoa P', 'U.idPessoa = P.idPessoa')->
                        where('U.login', '=', "?")->
                        where('P.nome', 'LIKE', "?");
        return $criteria;
    }

    /**
     * Query using distinct
     * @return <type>
     */
    public function criteriaMethod07() {
        $criteria = $this->getCriteria()->
                        select('P.nome')->
                        distinct()->
                        join('usuario U', 'pessoa P', 'U.idPessoa = P.idPessoa');
        return $criteria;
    }

    /**
     * Query using automatic association many-to-many
     * @return <type>
     */
    public function criteriaMethod08() {
        $criteria = $this->getCriteria()->
                        select('login, grupos.grupo');
        return $criteria;
    }

    /**
     * Query using groupBy and agregations
     * @return <type>
     */
    public function criteriaMethod09() {
        $criteria = $this->getCriteria()->
                        select('grupos.grupo, count(idUsuario)')->
                        groupBy('grupos.grupo')->
                        having("count(idUsuario) > 5");
        return $criteria;
    }

    /**
     * Query using IN operator
     * @return <type>
     */
    public function criteriaMethod10() {
        $logins = array('abc', 'cde', 'fgf');
        $criteria = $this->getCriteria()->
                        select('login')->
                        where('login', 'IN', $logins);
        return $criteria;
    }

    /**
     * Query with auto-association
     * @return <type>
     */
    public function criteriaMethod11() {
        $criteria = $this->getCriteria()->
                        select('U1.login')->
                        autoAssociation('U1', 'U2', 'U1.login = U2.login');
        return $criteria;
    }

    /**
     * Query with subquery
     * @return <type>
     */
    public function criteriaMethod12() {
        $parameter = '4%';
        $subCriteria = $this->getCriteria()->
                        select('idUsuario')->
                        where("login LIKE ?");
        $criteria = $this->getCriteria()->
                        select('login')->
                        where('idUsuario', 'IN', $subCriteria)->
                        parameters($parameter);
        return $criteria;
    }

    /**
     * Query with referenced subquery
     * @return <type>
     */
    public function criteriaMethod13() {
        $subCriteria = $this->getCriteria()->
                        select('count(idUsuario)')->
                        where("idSetor = S.idSetor");
        $setor = new Setor();
        $criteria = $setor->getCriteria()->
                        select('nome')->
                        setAlias('S')->
                        where($subCriteria, '>', '150');
        return $criteria;
    }

    /**
     * Query using outer on automatic join
     * @return <type>
     */
    public function criteriaMethod14() {
        $setor = new Setor();
        $criteria = $setor->getCriteria()->
                        associationType('usuarios', 'left')->
                        select('nome, count(usuarios.idUsuario)')->
                        where("login like 'A%'")->
                        groupBy('nome')->
                        having("count(usuarios.idUsuario) = 0");
        return $criteria;
    }

    /**
     * Query bypassing superclass.
     * @return <type>
     */
    public function criteriaMethod15() {
        $criteria = $this->getCriteria()->
                        select('login,A.matricula')->
                        join('usuario', 'aluno A', 'usuario.idPessoa=A.idPessoa', 'left');
        return $criteria;
    }

    /**
     * Query with compound conditions.
     * @return <type>
     */
    public function criteriaMethod16() {
        $criteria = $this->getCriteria()->
                        select('login')->
                        condition(
                            array('', 'login', 'LIKE', "'N%'"), 
                            array('AND', array(
                                array('', 'login', 'LIKE', "'C%'"),
                                array('OR', 'login', 'LIKE', "'D%'")
                            )
                        )
        );
        return $criteria;
    }

    /**
     * Named Parameters with arrays.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod17($filter) {
        $filter->nome = '%NICK%';
        $criteria = $this->getCriteria()->
                        select('idUsuario, login')->
                        where('upper(login)', 'LIKE', ':nome')->
                        or_('upper(pessoa.nome)', 'LIKE', ':nome')->
                        parameters(array('nome' => $filter->nome));
        return $criteria;
    }

    /**
     * Named Parameters with objects.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod18($filter) {
        $filter->nome = '%NICK%';
        $criteria = $this->getCriteria()->
                        select('idUsuario, login')->
                        where('upper(login)', 'LIKE', ':login')->
                        or_('upper(pessoa.nome)', 'LIKE', ':nome')->
                        parameters($filter);
        return $criteria;
    }

    /**
     * Table criteria.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaMethod19($filter) {
        $filter->nome = '%NICK%';
        $fromCriteria = $this->getCriteria()->
                        select('idUsuario, login, pessoa.nome as nome');
        $criteria = $this->getCriteria()->select('usuarios.login,usuarios.nome')->
                tableCriteria($fromCriteria, 'usuarios')->
                from('Usuario')->
                where('upper(usuarios.nome)', 'LIKE', ':nome')->
                or_('upper(usuarios.login)', 'LIKE', ':login')->
                parameters($filter);
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
        $criteria = $this->getCriteria(
                        "select *, (pessoa.nome || pessoa.email) as nome " .
                        "where pessoa.idPessoa = 1 " .
                        "order by pessoa.nome"
        );
        return $criteria;
    }

    /**
     * Query with automatic join and test parameter.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaCommand02($filter) {
        $criteria = $this->getCriteria(
                        "select idUsuario, login, pessoa.nome " .
                        "where login = '{$filter->login}'"
        );
        return $criteria;
    }

    /**
     * Query with forced join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaCommand03($filter) {
        $criteria = $this->getCriteria(
                        "select idUsuario, login " .
                        "from usuario join pessoa on (usuario.idPessoa=pessoa.idPessoa) " .
                        "where login = '{$filter->login}'"
        );
        return $criteria;
    }

    /**
     * Query with alias and automatic join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaCommand04($filter) {
        $criteria = $this->getCriteria(
                                "select U.idUsuario, P.nome " .
                                "where U.login = '{$filter->login}'")->
                        setAlias('U')->
                        setAssociationAlias('pessoa', 'P');
        return $criteria;
    }

    /**
     * Query with alias and forced join.
     * @param <type> $filter
     * @return <type>
     */
    public function criteriaCommand05($filter) {
        $criteria = $this->getCriteria(
                        "select U.idUsuario, P.nome " .
                        "from usuario U join pessoa P on (U.idPessoa = P.idPessoa) " .
                        "where (U.login = '{$filter->login}')"
        );
        return $criteria;
    }

    /**
     * Query using parameters
     * @return <type>
     */
    public function criteriaCommand06() {
        $criteria = $this->getCriteria(
                        "select U.idUsuario, P.nome " .
                        "from usuario U join pessoa P on (U.idPessoa = P.idPessoa) " .
                        "where (U.login = ?) and (P.nome LIKE ?)"
        );
        return $criteria;
    }

    /**
     * Query using distinct
     * @return <type>
     */
    public function criteriaCommand07() {
        $criteria = $this->getCriteria(
                        "select distinct P.nome " .
                        "from usuario U join pessoa P on (U.idPessoa = P.idPessoa)"
        );
        return $criteria;
    }

    /**
     * Query using automatic association many-to-many
     * @return <type>
     */
    public function criteriaCommand08() {
        $criteria = $this->getCriteria(
                        "select login, grupos.grupo"
        );
        return $criteria;
    }

    /**
     * Query using groupBy and agregations
     * @return <type>
     */
    public function criteriaCommand09() {
        $criteria = $this->getCriteria(
                        "select grupos.grupo, count(idUsuario) " .
                        "group by grupos.grupo " .
                        "having count(idUsuario) > 5"
        );
        return $criteria;
    }

    /**
     * Query using IN operator
     * @return <type>
     */
    public function criteriaCommand10() {
        $logins = new \OperandArray(array('abc', 'cde', 'fgf'));
        $criteria = $this->getCriteria(
                        "select login " .
                        "where login IN " . $logins->getSql()
        );
        return $criteria;
    }

    /**
     * Query with auto-association
     * @return <type>
     */
    public function criteriaCommand11() {
        $criteria = $this->getCriteria(
                        "select U1.login " .
                        "from usuario U1 join usuario U2 on (U1.login = U2.login)"
        );
        return $criteria;
    }

    /**
     * Query with subquery
     * @return <type>
     */
    public function criteriaCommand12() {
        $parameter = 'A%';
        $criteria = $this->getCriteria(
                        "select login " .
                        "where idUsuario IN (" . "select idUsuario where login LIKE ?" . ")"
                )->parameters($parameter);
        return $criteria;
    }

    /**
     * Query with referenced subquery
     * @return <type>
     */
    public function criteriaCommand13() {
        $setor = new Setor();
        $criteria = $this->getCriteria(
                                "select S.nome " .
                                "from setor S " .
                                "where (select count(U.idUsuario) from usuario U where U.idSetor = S.idSetor) > 150")->
                        addClass('\exemplos\models\setor', 'S');
        return $criteria;
    }

    /**
     * Query using outer on automatic join
     * @return <type>
     */
    public function criteriaCommand14() {
        $setor = new Setor();
        $criteria = $setor->getCriteria(
                        "select nome, count(usuarios.idUsuario) " .
                        "where login like 'A%' " .
                        "group by nome " .
                        "having count(usuarios.idUsuario) = 0"
                )->setAssociationType('usuarios', 'left');
        return $criteria;
    }

    /**
     * Query bypassing superclass.
     * @return <type>
     */
    public function criteriaCommand15() {
        $criteria = $this->getCriteria(
                        "select login,A.matricula " .
                        "from usuario left join aluno A on (usuario.idPessoa = A.idPessoa)"
        );
        return $criteria;
    }

    /**
     * Query with compound conditions.
     * @return <type>
     */
    public function criteriaCommand16() {
        $criteria = $this->getCriteria(
                        "select login " .
                        "where (login LIKE 'N%') or ((login LIKE 'C%') or (login LIKE 'D%'))"
        );
        return $criteria;
    }

    /**
     * ------------------ CRUD Methods
     */

    /**
     * CRUD operations.
     */
    public function methods() {
        // retrieve by Id
        $this->getById(1);
        // lazy load
        $nome = $this->getPessoa()->getNome();
        mdump('nome = ' . $nome);
        // update
        $this->setLogin('Novo Login');
        $this->setPassword('123456');
        $this->save();
        // cria novo usuario com os mesmos dados
        $this->setPersistent(false);
        $this->setIdusuario(NULL);
        $this->save();
        // obtem o id do novo objeto
        $id = $this->getId();
        // delete
        $this->delete();
    }

    /**
     * ------------------ Transactions
     */

    /**
     * Operações CRUD são executadas dentro de uma transação automatica
     */
    public function automaticTransaction() {
        $this->getById(1);
        $this->save();
    }

    /**
     * Operações CRUD são executadas dentro de transações separadas
     */
    public function twoOperations() {
        $this->getById(1);
        $this->save();
        $this->setPersistent(false);
        $this->save();
    }

    /**
     * Duas operações CRUD dentro da mesma transação
     */
    public function forcedTransaction() {
        try {
            $transaction = $this->beginTransaction();
            $this->getById(1);
            $this->save();
            // cria novo usuario com os mesmos dados
            $this->setPersistent(false);
            $this->save();
            $transaction->commit();
        } catch (EModelException $e) {
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
    }

    /**
     * Duas operações com objetos diferentes, usando transações diferentes
     */
    public function twoObjects() {
        try {
            // cria um novo setor
            $setor = new Setor();
            $setor->novo('NOVO SETOR');
            // cria um novo usuário
            $this->getById(1);
            $this->setPersistent(false);
            $this->setIdSetor($setor->getIdSetor());
            $this->save();
        } catch (EModelException $e) {
            throw new EModelException($e->getMessage());
        }
    }

    /**
     * Duas operações com objetos diferentes, usando a mesma transação
     */
    public function nestedTransactions() {
        try {
            $transaction = $this->beginTransaction();
            $this->getById(1);
            $this->save();
            // cria um novo usuário com um novo setor
            $setor = new Setor();
            $setor->novo('NOVO SETOR');
            $this->setPersistent(false);
            $this->setIdSetor($setor->getIdSetor());
            $this->save();
            $transaction->commit();
        } catch (EModelException $e) {
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
    }

    /**
     * ------------------ Operações de conjunto
     */

    /**
     * UNION 
     */
    public function unionOperation() {
        $aluno = new Aluno();
        $criteriaAluno = $aluno->getCriteria()->select('idPessoa');
        $criteriaUsuario = $this->getCriteria()->select('idPessoa');
        $criteriaUsuario->setOperation('UNION', $criteriaAluno);
        return $criteriaUsuario;
    }

    /**
     * INTERSECT
     */
    public function intersectOperation() {
        $aluno = new Aluno();
        $criteriaAluno = $aluno->getCriteria()->select('idPessoa');
        $criteriaUsuario = $this->getCriteria()->select('idPessoa');
        $criteriaUsuario->setOperation('INTERSECT', $criteriaAluno);
        return $criteriaUsuario;
    }

    /**
     * DIFFERENCE
     */
    public function differenceOperation() {
        $aluno = new Aluno();
        $criteriaAluno = $aluno->getCriteria()->select('idPessoa');
        $criteriaUsuario = $this->getCriteria()->select('idPessoa');
        $criteriaUsuario->setOperation('MINUS', $criteriaAluno);
        return $criteriaUsuario;
    }

}

?>
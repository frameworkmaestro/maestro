<?php

/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

namespace Maestro\Database;

use Maestro,
    Doctrine\DBAL;

class MDatabase {

    /**
     * List of supported drivers and their mappings to the driver classes.
     *
     * @var array
     */
    private static $_driverClass = array(
        'sqlite3' => '\\Maestro\\Database\\Driver\\SQLite3\\Driver',
        'oci8' => '\\Maestro\\Database\\Driver\\OCI8\\Driver',
    );
    private static $_platformMap = array(
        'pdo_mysql' => '\\Maestro\\Database\\Platforms\\PDOMySql\\Platform',
        'pdo_sqlite' => '\\Maestro\\Database\\Platforms\\PDOSqlite\\Platform',
        'pdo_pgsql' => '\\Maestro\\Database\\Platforms\\PDOPgSQL\\Platform',
        'oci8' => '\\Maestro\\Database\\Platforms\\Oci8\\Platform',
        //'oracle8' => '\\Maestro\\Database\\Platforms\\Oci8\\Platform',
        'pdo_sqlsrv' => '\\Maestro\\Database\\Platforms\\PDOSqlsrv\\platform',
        'sqlsrv' => '\\Maestro\\Database\\Platforms\\SQLSrv\\Platform',
        'sqlite3' => '\\Maestro\\Database\\Platforms\\SQLite3\\Platform',
    );
    private $config;       // identifies db configuration in conf.php
    private $params;
    private $connection;   // Doctrine\DBAL\Connection object
    private $status;       // 'open' or 'close'
    private $platform;     // platform of current driver
    private $transaction;
    private $name;
    private $ormLogger = NULL;
    private $lastInsertId = 0;

    public function __construct($name = 'default') {
        try {
            $this->name = trim($name);
            $this->config = \Manager::getConf("db.{$name}");
            $platform = self::$_platformMap[$this->config['driver']];
            $this->platform = new $platform($this);
            $this->config['platform'] = $this->platform;
            $driver = self::$_driverClass[$this->config['driver']];
            if ($driver != '') {
                $this->config['driverClass'] = $driver;
                unset($this->config['driver']);
            }
            $this->connection = $this->newConnection();
            $this->params = $this->connection->getParams();
            $this->platform->connect();
            $ormLogger = $this->config['ormLoggerClass'];
            if ($ormLogger) {
                $this->ormLogger = new $ormLogger();
            }
        } catch (\Exception $e) {
            throw new EDBException('Erro na conexão com o banco de dados.');
        }
    }

    /**
     * Get an instance of a DBAL Connection
     *
     * @param sting $name the connection name
     * @return Doctrine\DBAL\Connection
     */
    public function newConnection() {
        $configuration = new DBAL\Configuration();
        $logger = new MSQLLogger($this);
        $configuration->setSQLLogger($logger);
        return DBAL\DriverManager::getConnection($this->config, $configuration);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getConfig($key) {
        $k = explode('.', $key);
        $conf = $this->config;
        foreach ($k as $token) {
            $conf = $conf[$token];
        }
        return $conf;
    }

    public function getName() {
        return $this->name;
    }

    public function getPlatform() {
        return $this->platform;
    }

    public function getORMLogger() {
        return $this->ormLogger;
    }

    public function getTransaction() {
        return $this->connection;
    }

    public function lastInsertId() {
        return $this->lastInsertId;
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
        return $this->connection;
    }

    public function getSQL($columns = '', $tables = '', $where = '', $orderBy = '', $groupBy = '', $having = '', $forUpdate = false) {
        $sql = new MSQL($columns, $tables, $where, $orderBy, $groupBy, $having, $forUpdate);
        $sql->setDb($this);
        return $sql;
    }

    public function execute(MSQL $sql, $parameters = NULL) {
        if ($this->connection->isTransactionActive()) {
            try {
                $sql->setParameters($parameters);
                $this->affectedRows = $sql->execute();
            } catch (\Exception $e) {
                $code = $sql->stmt->errorCode();
                $info = $sql->stmt->errorInfo();
                throw EDBException::execute($info[2], $code);
            }
        } else {
            throw EDBException::transaction('Não é possível executar comandos fora de uma transação ativa.');
        }
        return $ok;
    }

    public function executeBatch(/* array of MSQL */ $sqlArray) {
        if (!is_array($sqlArray)) {
            $sqlArray = array($sqlArray);
        }
        try {
            $this->beginTransaction();
            foreach ($sqlArray as $sql) {
                if (is_array($sql)) {
                    foreach ($sql as $data) {
                        $platForm = $data[0];
                        $platForm->handleTypedAttribute($data[1], $data[2], $data[3]);
                    }
                } else {
                    $this->execute($sql);
                }
            }
            $this->lastInsertId = $this->connection->lastInsertId();
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw EDBException::execute($e->getMessage());
        }
    }

    public function executeCommand($command, $parameters = null) {
        $msql = new MSQL();
        $msql->setDb($this);
        $msql->setCommand($command);
        $this->execute($msql, $parameters);
    }

    public function count(MQuery $query) {
        return $query->count();
    }

    public function getNewId($sequence = 'admin') {
        try {
            $value = $this->platform->getNewId($sequence);
        } catch (\Exception $e) {
            throw EDBException::execute('DB::getNewId: ' . trim($e->getMessage()));
        }
        return $value;
    }

    public function prepare(MSQL $sql) {
        $sql->prepare();
    }

    public function query(MSQL $sql) {
        try {
            $query = $this->getQuery($sql);
            return $query->fetchAll();
        } catch (\Exception $e) {
            throw EDBException::query($e->getMessage());
        }
    }

    public function executeQuery($command, $parameters = null, $page = null, $rows = null) {
        try {
            $query = new MQuery();
            $query->setDb($this);
            $msql = new MSQL();
            $msql->setCommand($command);
            if ($parameters) {
                $msql->setParameters($parameters);
            }
            if ($page) {
                $msql->setRange($page, $rows);
            }
            $query->setSQL($msql);
            return $query->fetchAll();
        } catch (\Exception $e) {
            throw EDBException::query($e->getMessage());
        }
    }

    public function getQueryCommand($command) {
        try {
            $query = new MQuery();
            $query->setDb($this);
            $msql = new MSQL();
            $msql->setCommand($command);
            $query->setSQL($msql);
            return $query;
        } catch (\Exception $e) {
            throw EDBException::query($e->getMessage());
        }
    }

    public function getQuery(MSQL $sql) {
        try {
            $query = new MQuery();
            $query->setDb($this);
            //$query->setConnection($this->getConnection());
            $query->setSQL($sql);
            return $query;
        } catch (\Exception $e) {
            throw EDBException::query($e->getMessage());
        }
    }

    public function getTable($tableName) {
        try {
            $sql = new MSql("*", $tableName);
            $query = $this->getQuery($sql);
            return $query;
        } catch (\Exception $e) {
            throw EDBException::query($e->getMessage());
        }
    }

    public function executeProcedure($sql, $aParams = array(), $aResult = array()) {
        /* TODO */
    }

}

?>
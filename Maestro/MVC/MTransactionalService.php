<?php

namespace Maestro\MVC;

use Maestro\Manager;

abstract class MTransactionalService extends MBaseService
{
    private $database;
    protected $databaseName;
    protected $transaction;

    public function __construct($databaseName)
    {
        parent::__construct();
        $this->databaseName = $databaseName;
    }

    public function getDatabase() {
        if (is_null($this->database)) {
            $this->database = Manager::getDatabase($this->databaseName);
        }
        return $this->database;
    }

    /**
     * Coloca a conexão indicada em estado de transação.
     */
    public function beginTransaction() {
        $this->transaction = $this->getDatabase()->beginTransaction();
    }

    public function commit() {
        $this->transaction->commit();
    }

    public function rollback() {
        $this->transaction->rollback();
    }

    public function execute($parameters){
        $that = $this;
        return $this->transactional(function () use ($that, $parameters) {
            return $that->run($parameters);
        });
    }

    public function transactional(callable $operation) {
        $this->beginTransaction();
        try{
            $result = call_user_func($operation);
            $this->commit();
            return $result;
        } catch(Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

}

<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Exemplos',
    'instituicao' => 'Framework Maestro 2.0',
    'options' => array(
        'painter' => 'painter',
    ),
    'theme' => array(
        'app' => 'exemplos',
        'name' => 'exemplos',
        'template' => 'index'
    ),
    'login' => array(
        'module' => "",
        'class' => "MAuthDbMD5",
        'check' => false
    ),
    'filters' => array(
        'session',
        'profile',
    ),
    'db' => array(
        /* Postgres 
        'exemplos' => array(
            'driver' => 'pdo_pgsql',
            'host' => 'localhost',
            'dbname' => 'exemplos',
            'user' => 'postgres',
            'password' => 'pg-admin',
            'formatDate' => 'DD/MM/YYYY',
            'formatTime' => 'HH24:MI:SS',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
         */
        /* SQLite   */
        'exemplos' => array(
            'driver' => 'sqlite3',
            'path' => realpath(Maestro\Manager::getAppPath('models/sql/exemplos.db')),
            'formatDate' => '%d/%m/%Y',
            'formatTime' => '%H:%M:%S',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
    ),
);

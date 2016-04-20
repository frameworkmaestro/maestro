<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Guia',
    'instituicao' => 'Framework Maestro',
    'import' => array(
        'models.*'
    ),
    'options' => array(
        'fetchStyle' => \FETCH_ASSOC,
        'language' => 'en',
        'defaultPassword' => 'default',
        'pageTitle' => 'Maestro 2.0 - Guia do Usuário',
        'mainTitle' => 'Maestro 2.0 - Guia do Usuário'
    ),
    'theme' => array(
        'name' => 'guia',
        'template' => 'index'
    ),
    'ui' => array(
        'inlineFormAction' => true
    ),
    'login' => array(
        'module' => "",
        'class' => "MAuthDbMd5",
        'check' => false
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
        ), */
        /* SQLite  */
        'exemplos' => array(
            'driver' => 'sqlite3',
            'path' => Manager::getAppPath('models/sql/exemplos.db'),
            'formatDate' => '%d/%m/%Y',
            'formatTime' => '%H:%M:%S',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
    ),
);

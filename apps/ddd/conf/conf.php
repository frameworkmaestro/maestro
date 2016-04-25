<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'DDD',
    'instituicao' => 'Framework Maestro',
    'import' => array(
        'models.*'
    ),
    'options' => array(
        'persistence' => 'maestro',
        'fetchStyle' => \FETCH_ASSOC,
        'language' => 'en',
        'defaultPassword' => 'default',
        'pageTitle' => 'Maestro 2.0 - DDD',
        'mainTitle' => 'Maestro 2.0 - DDD'
    ),
    'theme' => array(
        'name' => 'ddd',
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
        /* SQLite
        'ddd' => array(
            'driver' => 'sqlite3',
            'path' => Manager::getAppPath('src/models/db/ddd.db'),
            'formatDate' => '%d/%m/%Y',
            'formatTime' => '%H:%M:%S',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
        */
        /* MySql */
        'ddd' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'ddd',
            'user' => 'fnbrasil',
            'password' => 'OssracF1982',
            'formatDate' => '%d/%m/%Y',
            'formatDateWhere' => '%d/%m/%Y',
            'formatTime' => '%T',
            'charset' => 'UTF8',
            'sequence' => array(
                'table' => 'manager_sequence',
                'name' => 'sequence',
                'value' => 'value'
            ),
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
    ),
);

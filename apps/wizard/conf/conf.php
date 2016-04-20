<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Maestro Wizard',
    'import' => array(
        'models.*'
    ),
    'options' => array(
        'painter' => 'painter',
        'basePath' => Manager::getHome() . '/var/wizard/',
        'pageTitle' => 'Maestro 2.0 Wizard',
        'mainTitle' => 'Maestro 2.0 Wizard'
    ),
    'theme' => array(
        'name' => 'wizard',
        'template' => 'index'
    ),
    'login' => array(
        'module' => "",
        'class' => "MAuthDbMd5",
        'check' => false
    ),
    'db' => array(
        // Exemplo de configuração para Engenharia Reversa de banco MySQL
        'teste' => array(
            'driver' => 'pdo_mysql',
            'host' => 'host',
            'dbname' => 'db',
            'user' => 'x',
            'password' => 'y',
            'charset' => 'UTF8',
        ),
        'fnapolo' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'fnapolo_db',
            'user' => 'fnbrasil',
            'password' => 'OssracF1982',
            'charset' => 'UTF8',
        ),
        'mundial' => array(
            'driver' => 'pdo_mysql',
            'host' => '192.64.114.106',
            'dbname' => 'mundial',
            'user' => 'mundial',
            'password' => 'm5212592',
            'formatDate' => 'DD/MM/YYYY',
            'formatTime' => 'HH24:MI:SS',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
        'mknob' => array(
            'driver' => 'pdo_mysql',
            'host' => '127.0.0.1',
            'dbname' => 'mknob_db',
            'user' => 'fnbrasil',
            'password' => 'OssracF1982',
            'formatDate' => '%e/%m/%Y',
            'formatDateWhere' => '%Y/%m/%e',
            'formatTime' => '%T',
            'charset' => 'UTF8',
            'sequence' => array(
                'table' => 'Sequence',
                'name' => 'Name',
                'value' => 'Value'
            ),
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
        'kancolle' => array(
            'charset' => 'UTF8',
            'driver' => 'pdo_mysql',
            'dbname' => 'library',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => '0',
            'formatDate' => 'DD/MM/YYYY',
            'formatTime' => 'HH24:MI:SS',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
            'sequence' => array(
                'table' => 'Sequence',
                'name' => 'name',
                'value' => 'value'
            ),
        ),

        
    ),
);

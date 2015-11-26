<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Maestro Wizard',
    'import' => array(
        'models.*'
    ),
    'options' => array(
        'painter' => 'painter',
        'basePath' => Manager::getHome() . '/core/var/wizard/',  
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

        
    ),
);

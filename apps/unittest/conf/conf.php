<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Unit Test',
    'theme' => array(
        'name' => 'fnbr20',
        'template' => 'index'
    ),
    'login' => array(
        'module' => "auth",
        'class' => "MAuthDbMd5",
        'check' => false
    ),
    'filters' => array(),
    'db' => array(
        'unittest' => array(
            'driver' => 'pdo_mysql',
            'host' => '192.64.114.106',
            'dbname' => 'net_unittest',
            'user' => 'net_mysql',
            'password' => 'n5212592',
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
    'path' => array(
        'maestro1' => '/home/fmatos/public_html/maestro/'
    )
);

<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Template App',
    'import' => array(
        'models.*'
    ),
    'theme' => array(
        'name' => 'default',
        'template' => 'content'
    ),
    'login' => array(
        'module' => "",
        'class' => "MAuthDbMd5",
        'check' => false
    ),
    'db' => array(
    ),
    
);

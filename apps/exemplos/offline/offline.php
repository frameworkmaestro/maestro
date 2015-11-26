<?php
// Diretorio do script corrente
$dir = dirname(__FILE__);

// Path do Maestro
$dir = strstr($dir, "maestro20", true) . "maestro20";
require_once($dir . '/vendor/autoload.php');


// Configuração para tratamento de erros
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set("log_errors", "on");
ini_set("error_log", $dir . "core/var/log/php_error.log");


// Inclusão do framework
$conf = $dir . '/core/conf/conf.php';
require_once($dir . '/core/classes/Manager.php');
\Maestro\Manager::getInstance();

\Maestro\Manager::init($conf, $dir);
//set_error_handler('Maestro\Manager::errorHandler');
//\Maestro\Manager::processRequest();

//
// Inicialização do framework
//Manager::init($conf, $dir);
//Manager::loadConf(Manager::getAbsolutePath("apps/library/conf/conf.php"));
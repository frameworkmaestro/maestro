<?php

ini_set("log_errors", "on");
ini_set("error_log", "var/log/php_error.log");

// Diretório base do Maestro
$dir = dirname(__FILE__);
// Arquivo de configuração
$conf = dirname(__FILE__) . '/conf/conf.php';

require_once($dir . '/vendor/autoload.php');

\Maestro\Manager::process($conf, $dir);

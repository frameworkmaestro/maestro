<?php

/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Framework Maestro 2.0',
    'contact' => 'Administrador',
    // preloading 'log' component
    'preload' => array('log'),
    'options' => array(
        'startup' => 'guia',
        'dbsession' => false,
        'charset' => 'UTF-8',
        'timezone' => "America/Sao_Paulo",
        'separatorDate' => '/',
        'formatDate' => 'd/m/Y',
        'formatTimestamp' => 'd/m/Y H:i:s',
        'csv' => ';',
        'mode' => 'DEV',
        'fetchStyle' => \FETCH_NUM,
        'dispatcher' => 'index.php',
        'javaPath' => '/opt/java',
        'javaBridge' => 'http://localhost:8080/JavaBridge/java/Java.inc',
        'language' => 'en',
        'locale' => array("pt_BR.utf8", "ptb") // no linux verificar os locales instalados com "locale -a"
    ),
    'autoload' => array(
      'ignore' => array('ProxyManager')
    ),
    'mad' => array(
        'module' => "auth",
        'access' => "acesso",
        'group' => "grupo",
        'log' => "log",
        'session' => "sessao",
        'transaction' => "transacao",
        'user' => "usuario"
    ),
    'debug' => array(
        'enabled' => true,
        'severity' => E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT,
        'maxLen' => 500,
        'maxDepth' => 5,
        'strictMode' => FALSE
    ),
    'login' => array(
        'module' => "exemplo",
        'auth' => "Maestro\\Security\\MAuthDbMD5",
        'perms' => "Maestro\\Security\\MPerms",
        'check' => false,
        'shared' => true,
        'auto' => false
    ),
    'session' => array(
        'handler' => "file",
        'timeout' => "30",
        'check' => true
    ),
    'logs' => array(
        'path' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'log',
        'level' => 2, // 0 (nenhum), 1 (apenas erros) ou 2 (erros e SQL)
        'handler' => "socket",
		'peer' => '127.0.0.1',
        'strict' => '',
        'port' => 0 //Default 9999
    ),
    'mailer' => array(
        'smtpServer' => 'smtp.x.com',
        'smtpFrom' => 'x@x.com',
        'smtpFromName' => 'UFJF',
        'smtpAuthUser' => '',
        'smtpAuthPass' => '',
    ),
    'extensions' => array(
    ),
    'db' => array(
        'configLoader' => 'PHP',
        'manager' => array(
            'driver' => 'pdo_pgsql',
            'host' => 'localhost',
            'dbname' => 'exemplos',
            'user' => 'postgres',
            'password' => 'pgadmin',
            'formatDate' => 'DD/MM/YYYY',
            'formatDateWhere' => 'YYYY/MM/DD',
            'formatTime' => 'HH24:MI:SS',
            'configurationClass' => 'Doctrine\DBAL\Configuration',
        ),
   ),
    'types' => array(
        'blob' => 'Maestro\Types\MBlob',
        'cpf' => 'Maestro\Types\MCPF',
        'cnpj' => 'Maestro\Types\MCNPJ',
        'currency' => 'Maestro\Types\MCurrency',
        'date' => 'Maestro\Types\MDate',
        'file' => 'Maestro\Types\MFile',
        'nit' => 'Maestro\Types\MNIT',
        'password' => 'Maestro\Types\MPassword',
        'timestamp' => 'Maestro\Types\MTimestamp'
    )
);

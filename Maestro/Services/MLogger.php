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

namespace Maestro\Services;

use Maestro\Manager,
    \Psr\Log\LogLevel;

/**
 * Logger.
 */
class MLogger extends \Psr\Log\AbstractLogger
{

    /**
     * Attribute Description.
     */
    private $baseDir;

    /**
     * Indica o nivel de emissão das mensagens de log: 0 (nenhum), 1 (apenas erros) ou 2 (erros e SQL)
     */
    private $level;

    /**
     * Attribute Description.
     */
    private $handler;

    /**
     * Attribute Description.
     */
    private $port;

    /**
     * Attribute Description.
     */
    private $socket;

    /**
     * Attribute Description.
     */
    private $host;

    /**
     * Attribute Description.
     */
    private $peer;

    /**
     * Attribute Description.
     */
    private $strict;

    public function __construct()
    {
        $conf = Manager::getConf('logs');
        $this->baseDir = $conf['path'];
        $this->level = $conf['level'];
        $this->handler = $conf['handler'];
        $this->port = $conf['port'];
        $this->peer = $conf['peer'];
        $this->strict = $conf['strict'];
        if (empty($this->host)) {
            $this->host = $_SERVER['REMOTE_ADDR'];
        }
    }

    public function getLogFileName($filename)
    {
        $dir = $this->baseDir;
        $dir .= "/maestro";
        $filename = basename($filename) . '.' . date('Y') . '-' . date('m') . '-' . date('d') . '-' . date('H') . '.log';
        $file = $dir . '/' . $filename;
        return $file;
    }

    /**
     * Indica se a geração de logs está habilitada.
     * Complete Description.
     *
     * @returns boolean
     * 
     */
    public function isLogging()
    {
        return ($this->level > 0);
    }

    /**
     * Implementa o método log, de LoggerInterface.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->isLogging()) {
            if (count($context) > 0) {
                $message = $this->interpolate($message, $context);
            }
            $handler = "Handler" . $this->handler;
            $this->{$handler}($message);
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function logMessage($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $sql (tipo) desc
     * @param $force (tipo) desc
     * @param $conf= (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function logSQL($sql, $db, $force = false)
    {
        if ($this->level < 2) {
            return;
        }

        // agrega múltiplas linhas em uma só
        $sql = preg_replace("/\n+ */", " ", $sql);
        $sql = preg_replace("/ +/", " ", $sql);

        // elimina espaços no início e no fim do comando SQL
        $sql = trim($sql);

        // troca aspas " em ""
        $sql = str_replace('"', '""', $sql);

        // data/hora no formato "dd/mes/aaaa:hh:mm:ss"
        $context['dts'] = Manager::getSysTime();

        // comandos a serem logados
        $cmd = "/(SELECT|INSERT|DELETE|UPDATE|ALTER|CREATE|BEGIN|START|END|COMMIT|ROLLBACK|GRANT|REVOKE)(.*)/";

        if ($force || preg_match($cmd, $sql)) {
            $context['conf'] = trim($db->getName());
            $context['ip'] = substr($this->host . '        ', 0, 15);
            $login = Manager::getLogin();
            $context['uid'] = sprintf("%-10s", ($login ? $login->getLogin() : ''));
            $message = $this->interpolate("[{dts}] {ip} - {conf} - {uid} : \"{$sql}\"", $context);
            $logfile = $this->getLogFileName($context['conf'] . '-sql');
            error_log($message . "\n", 3, $logfile);
            $this->logMessage('[SQL]' . $message);
        }

    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $error (tipo) desc
     * @param $conf (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function logError($error, $conf = 'maestro')
    {
        if ($this->level == 0) {
            return;
        }

        // data/hora no formato "dd/mes/aaaa:hh:mm:ss"
        $context['dts'] = Manager::getSysTime();
        $context['ip'] = sprintf("%15s", $this->host);
        $login = Manager::getLogin();
        $context['uid'] = sprintf("%-10s", ($login ? $login->getLogin() : ''));
        $message = $this->interpolate("[{dts}] {ip} - {uid} : \"{$error}\"", $context);
        $logfile = $this->getLogFileName($conf . '-error');
        error_log($message . "\n", 3, $logfile);
        $this->logMessage('[ERROR]' . $message);
    }

    /**
     * Interpola valores do contexto com os placeholders da mensagem.
     */
    private function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    private function handlerSocket($message)
    {
        $allow = $this->strict ? ($this->strict == $this->host) : true;
        $host = $this->peer ? : $this->host;
        if ($this->port && $allow) {
            if (!$this->socket) {
                $this->socket = fsockopen($host, $this->port);
                if (!$this->socket) {
                    $this->trace_socket = -1;
                }
            }
            $message = str_replace("\n\n","", $message);
            fputs($this->socket, $message . "\n");
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    private function handlerFile($message)
    {
        $logfile = $this->baseDir . '/' . trim($this->host) . '.log';
        $ts = Manager::getSysTime();
        error_log($ts . ': ' . $message . "\n", 3, $logfile);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    private function handlerDb($message)
    {
        $login = Manager::getLogin();
        $uid = ($login ? $login->getLogin() : '');
        $ts = Manager::getSysTime();
        $db = Manager::getDatabase('manager');
        $idLog = $db->getNewId('seq_manager_log');
        $sql = new MSQL('idlog, timestamp, login, msg, host', 'manager_log');
        $db->execute($sql->insert(array($idLog, $ts, $uid, $message, $this->host)));
    }

}

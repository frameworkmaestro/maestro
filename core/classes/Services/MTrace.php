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

use Maestro\Manager;

/**
 * Métodos para companhamento (trace) do fluxo de execução.
 * Esta classe implementamétodos que permitem acompanhar o fluxo de execução através da emissão de mensagens para o usuário (através da classe MLogger).
 */
class MTrace
{

    /**
     * Attribute Description.
     */
    private $trace = [];

    /**
     * Attribute Description.
     */
    private $logger;

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public function __construct()
    {
        $this->logger = Manager::getLog();
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     * @param $file (tipo) desc
     * @param $line=0 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function trace($msg, $file = '', $line = 0)
    {
        $message = $msg;
        if ($file != '') {
            $message .= " [file: $file] [line: $line]";
        }
        $this->trace[] = $message;
        $this->logger->logMessage('[TRACE]' . $message);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public function dump($msg, $file = '', $line = 0)
    {
        $message = $msg;
        if ($file != '') {
            $message .= " [file: $file] [line: $line]";
        }
        $this->trace[] = $message;
        $tag = Manager::getConf('logs')['tag'];
        if (strlen($tag) > 0) {
            $this->logger->logMessage('[' . $tag . ']' . $message);
        } else {
            $this->logger->logMessage('[CUSTOM]' . $message);
        }
    }

    public function traceStack($file = '', $line = 0)
    {
        try {
            throw new \Exception;
        } catch (\Exception $e) {
            $strStack = $e->getTraceAsString();
        }
        $this->trace($strStack, $file, $line);
        return $strStack;
    }

}

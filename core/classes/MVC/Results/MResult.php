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

namespace Maestro\MVC\Results;

use Maestro\Manager;

/**
 * MResult.
 * Classe abstrata, base para as classes de geração da resposta à requisição.
 */
abstract class MResult
{

    protected $ajax;
    protected $page;
    protected $content;

    public function __construct()
    {
        $this->ajax = Manager::getAjax();
        $this->page = Manager::getPage();
        $this->setContent();
    }

    public abstract function getOutput();

    protected function setContent()
    {
        $this->content = NULL;
    }

    protected function setContentTypeIfNotSet($response, $contentType)
    {
        $response->setContentTypeIfNotSet($contentType);
    }

    protected function nocache()
    {
        // headers apropriados para evitar caching
        Manager::$response->setHeader('Expires', 'Expires: Fri, 14 Mar 1980 20:53:00 GMT');
        Manager::$response->setHeader('Last-Modified', 'Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        Manager::$response->setHeader('Cache-Control', 'Cache-Control: no-cache, must-revalidate');
        Manager::$response->setHeader('Pragma', 'Pragma: no-cache');
        Manager::$response->setHeader('X-Powered-By', 'X-Powered-By: ' . Manager::version() . '/PHP ' . phpversion());
    }

}

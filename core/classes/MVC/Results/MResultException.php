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
 * MResultException.
 * Classe base para retornar os resultados de exceções.
 */
class MResultException extends MResult
{

    protected $exception;
    protected $template;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
        $this->setTemplate();
        parent::__construct();
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setTemplate()
    {
        if (Manager::DEV()) {
            throw $this->exception;
        } else {
            $language = Manager::getOptions('language');
            $path = Manager::getThemePath() . '/templates/errors' . ($language ? '/' . $language : '');
            $this->template = new \Maestro\UI\MTemplate($path);
            $this->template->context('manager', Manager::getInstance());
            $this->template->context('page', Manager::getPage());
            $this->template->context('charset', Manager::getOptions('charset'));
            $this->template->context('template', $this->template);
            $this->template->context('result', $this);
        }
    }

    public function fetch($templateName = '', $vars = array())
    {
        $html = $this->template->fetch($templateName . '.html', $vars);
        return $html;
    }

    public function getOutput()
    {
        $this->nocache();
        return $this->content;
    }

}

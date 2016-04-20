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

namespace Maestro\UI;

use Maestro\Manager;

/**
 * Brief Class Description.
 * Complete Class Description.
 */
class MTemplate extends MBase
{

    public $engine;
    public $context;
    public $path;
    public $template;
    public $painter;

    public function __construct($path = '')
    {
        parent::__construct();

        if (function_exists('mb_internal_charset')) {
            mb_internal_charset('UTF-8');
        }

        $this->engine = new \Latte\Engine;
        $this->path = ($path ? : Manager::getPublicPath() . '/templates');
        $this->engine->setTempDirectory(Manager::getFrameworkPath() . '/var/templates');
        $this->engine->getParser()->defaultSyntax = 'double';
        $this->engine->addFilter('translate', function ($s) {
            return _M($s);
        });
        $this->context = array();
        $this->context('manager', Manager::getInstance());
    }

    public function context($key, $value)
    {
        $this->context[$key] = $value;
    }

    public function multicontext($context = [])
    {
        foreach($context as $key => $value) {
            $this->context[$key] = $value;
        }    
    }
    
    public function load($fileName)
    {
        $this->template = $this->path . DIRECTORY_SEPARATOR . $fileName;
    }

    public function render($args = array())
    {
        $params = array_merge($this->context, $args);
        return $this->engine->renderToString($this->template, $params);
    }

    public function exists($fileName)
    {
        return file_exists($this->path . DIRECTORY_SEPARATOR . $fileName);
    }

    public function fetch($fileName, $args = array())
    {
        $this->load($fileName);
        return $this->render($args);
    }

}
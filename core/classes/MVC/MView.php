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

namespace Maestro\MVC;

use Maestro\Manager;

class MView {

    public $application;
    public $module;
    public $controller;
    public $viewFile;
    public $data;

    public function __construct($application, $module, $controller, $viewFile) {
        $this->application = $application;
        $this->module = $module;
        $this->controller = $controller;
        $this->viewFile = $viewFile;
    }

    public function init() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function setArgs($args) {
        if (count($args)) {
            foreach ($args as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    public function getPath() {
        return pathinfo($this->viewFile, PATHINFO_DIRNAME);
    }

    public function getControl($className) {
        return MControl::instance($className);
    }

    /**
     * Processa o arquivo da view e inclui o conteudo no objeto Page.
     * @param type $controller
     * @param type $parameters
     * @return type 
     */
    public function process($controller, $parameters) {
        mtrace('view file = ' . $this->viewFile);
        $path = $this->getPath();
        Manager::addAutoloadPath($path);
        $extension = pathinfo($this->viewFile, PATHINFO_EXTENSION);
        $this->controller = $controller;
        $this->data = $parameters;
        $process = 'process' . $extension;
        $content = $this->$process();
        Manager::getPage()->setContent($content);
    }

    private function processPHP() {
        $viewName = basename($this->viewFile, '.php');
        include_once $this->viewFile;
        $control = new $viewName();
        $control->setView($this);
        //$control->load();
        return $control;
    }

    private function processXML() {
        $container = new \Maestro\UI\MBaseControl();
        $container->setView($this);
        $container->getControlsFromXML($this->viewFile);
        return $container;
    }

    private function processTemplate() {
        $baseName = basename($this->viewFile);
        $template = new \Maestro\UI\MTemplate(dirname($this->viewFile));
        $template->context('manager', Manager::getInstance());
        $template->context('page', Manager::getPage());
        $template->context('view', $this);
        $template->context('data', $this->data);
        $template->context('template', $template);
        $template->context('painter', Manager::getPainter());
        return $template->fetch($baseName);
    }

    private function processHTML() {
        return $this->processTemplate();
    }

    private function processJS() {
        return $this->processTemplate();
    }

    private function processWiki() {
        $wikiPage = file_get_contents($this->viewFile);
        $wiki = new \Maestro\Utils\MWiki();
        return $wiki->parse('', $wikiPage);
    }

}

?>
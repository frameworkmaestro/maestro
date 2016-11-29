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
    public $view;

    public function __construct($application, $module, $controller, $viewFile) {
        $this->application = $application;
        $this->module = $module;
        $this->controller = $controller;
        $this->viewFile = $viewFile;
        $view = Manager::getConf('ui.view');
        mdump('>>>'. $view);
        $this->view = new $view;
        Manager::setView($this->view);
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

    public function process($controller, $parameters = null) {
        $this->view->process($controller, $this->viewFile, $parameters);
    }

    public function generate() {
        return $this->view->generate();
    }

    public function render() {
        return $this->view->render();
    }

}

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

/**
 * MAppStructure.
 * Classe que representa a estrutura de uma aplicação (modules, controllers, services, components, models).
 * Esta estrutura é inicializada quando a aplicação é executada pela primeira vez e 
 * é armazenada na sessão, para facilitar a localização das classes na execução de uma requisição.
 */
class MAppStructure
{

    public $app;
    public $folders;
    public $modules;
    public $controllers;
    public $services;
    public $components;
    public $filters;
    public $models;
    public $maps;
    public $basePath;
    public $basePathSrc;

    public function __construct($app, $basePath)
    {
        $this->app = $app;
        $this->basePath = $basePath;
        $basePathSrc = $basePath . DIRECTORY_SEPARATOR . 'src';
        $this->basePathSrc =  (file_exists($basePathSrc) ? $basePathSrc : $this->basePath) ;
        $this->loadFolders();
        $this->loadModules();
        $this->loadControllers();
        $this->loadServices();
        $this->loadComponents();
        $this->loadFilters();
        $this->loadModels();
        $this->loadMaps();
    }
    
    private function getPaths($type, $exclude = []) {
        
    }

    public function loadFolders()
    {
        $base = $this->basePathSrc;
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            if (is_dir($path)) {
                $folder = strtolower($path);
                $this->folders[$folder] = $base . DIRECTORY_SEPARATOR . $path;
            }
        }
    }

    public function loadModules()
    {
        $base = $this->basePath . DIRECTORY_SEPARATOR . 'modules';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            $module = strtolower($path);
            $this->modules[$module] = new MAppStructure($module, $base . DIRECTORY_SEPARATOR . $module);
        }
    }

    public function loadControllers()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'controllers';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            $controller = strtolower(str_replace('Controller.php', '', $path));
            $fullPath = $base . DIRECTORY_SEPARATOR . $path;
            $this->controllers[$controller] = $path;
        }
    }

    public function loadServices()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'services';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            $service = strtolower(str_replace('Service.php', '', $path));
            $fullPath = $base . DIRECTORY_SEPARATOR . $path;
            $this->services[$service] = $path;
        }
    }

    public function loadComponents()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'components';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            if (strpos($path, '.php') !== false) {
                $component = strtolower(str_replace('.php', '', $path));
                $fullPath = $base . DIRECTORY_SEPARATOR . $path;
                $this->components[$component] = $path;
            }
        }
    }

    public function loadFilters()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'filters';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            $filter = strtolower(str_replace('Filter.php', '', $path));
            $fullPath = $base . DIRECTORY_SEPARATOR . $path;
            $this->filters[$filter] = $path;
        }
    }

    public function loadModels()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'models';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.','map','sql']);
        foreach ($scandir as $path) {
            $model = strtolower(str_replace('.php', '', $path));
            $fullPath = $base . DIRECTORY_SEPARATOR . $path;
            $this->models[$model] = $path;
        }
    }

    public function loadMaps()
    {
        $base = $this->basePathSrc . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'map';
        if (!file_exists($base)) {
            return;
        }
        $scandir = scandir($base) ? : [];
        $scandir = array_diff($scandir, ['..', '.']);
        foreach ($scandir as $path) {
            $map = strtolower(str_replace('.php', '', $path));
            $fullPath = $base . DIRECTORY_SEPARATOR . $path;
            $this->maps[$map] = $path;
        }
    }
    
    public function get($element) {
        return $this->$element;
    }

}

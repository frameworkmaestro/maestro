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
 * MApp.
 * Classe que representa uma aplicação em execução.
 */
class MApp
{

    protected static $structure;
    protected static $loader;
    protected static $context;
    protected static $app = '';
    protected static $module;
    protected static $path;
    protected static $pathModules;
    protected static $controllerAction;
    protected static $autoload;

    public static function contextualize()
    {
        $context = self::$context = new MContext(Manager::$request);
        $app = self::$app = Manager::$app = $context->getApp();
        //$appStructure = Manager::getSession()->container('appStructure');
        //if ($appStructure->$app == null) {
            $appStructure->$app = new MAppStructure($app, $context->getAppPath());
        //}
        $context->defineContext($appStructure->$app);
        self::$structure = $appStructure;
        self::$module = $context->getModule();
        self::$path = Manager::$appPath = $context->getAppPath();
        self::$pathModules = self::$path . '/modules';
        $autoload = self::$path . '/vendor/autoload.php';
        self::$loader = require $autoload;
    }

    public static function getAutoload()
    {
        return self::$autoload;
    }

    public static function getPath()
    {
        return self::$path;
    }

    public static function getPathModules()
    {
        return self::$pathModules;
    }

    public static function getContext()
    {
        return self::$context;
    }

    public static function getLoader()
    {
        return self::$loader;
    }

    public static function getStructure($app)
    {
        return self::$structure->$app;
    }

    public static function getFilters()
    {
        return self::$filters;
    }

    public static function handler()
    {
        self::init();
        self::prepare();
        $result = self::execute();
        self::terminate();
        return $result;
    }

    public static function init()
    {
        self::addConf();
        self::addActions();
        self::addMessages();
        self::$controllerAction = MFrontController::getForward();
    }

    public static function prepare()
    {
        Manager::addAutoloadPath(Manager::getThemePath() . '/classes');
        Manager::addAutoloadPath(self::$path . '/components');
        //Manager::addNamespacePath(self::$app, self::$path);
        // registra o modulo MAD, se ele existir
        $mad = Manager::getMAD();
        if ($mad != '') {
            //Manager::addAutoloadPath($mad, self::$pathModules . "/{$mad}");
        }
        // registra os modulos indicados em conf.php
        $registerModules = Manager::getConf('import.modules') ? : [];
        foreach ($registerModules as $module) {
            //Manager::addNamespacePath($module, "{self::$pathModules}/{$module}");
            Manager::addAutoloadPath(self::$pathModules . "/{$module}/components");
        }
        // se estiver executando um módulo
        if (($module = self::$module) != '') {
            // obtém o conf.php do módulo
            self::addModuleConf($module);
            // obtém as mensagens do módulo
            self::addModuleMessages($module);
            //Manager::addNamespacePath(self::$module, "{self::$pathModules}/{self::$module}");
            Manager::addAutoloadPath(self::$pathModules . "/{$module}/components");
        }
    }

    public static function execute()
    {
        // instancia o handler definido por $type e executa
        $method = 'get' . self::$context->getType();
        $handler = self::$method(self::$app, self::$module, self::$context->getHandler());
        $handler->execute();
        return $handler->getResult();
    }

    public static function getHandlerFile($app, $module = '', $type = '', $handler = '')
    {
        if ($module != '') {
            $array = self::$structure->$app->modules[$module]->$type;
            $basePath = self::$structure->$app->modules[$module]->basePath;
        } else {
            $array = self::$structure->$app->$type;
            $basePath = self::$structure->$app->basePath;
        }
        return $basePath . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR .$array[$handler];
    }

    public static function getController($app, $module, $controller)
    {
        $className = "{$controller}Controller";
        Manager::logMessage("[MApp::getController  {$className}]");
        if (Manager::$controllers[$className]) {
            Manager::logMessage("[getController  from cache]");
            return Manager::$controllers[$className];
        }
        $fileName = self::getHandlerFile($app, $module, 'controllers', $controller);
        mdump($fileName);
        include_once $fileName;
        $handler = new $className(self::$context);
        mdump(get_class($handler));
        Manager::$controllers[$className] = $handler;
        return $handler;
    }

    public static function getService($app, $module, $service)
    {
        $className = "{$service}Service";
        Manager::logMessage("[MApp::getService  {$className}]");
        if (Manager::$controllers[$className]) {
            Manager::logMessage("[getService from cache]");
            return Manager::$controllers[$className];
        }
        $fileName = self::getHandlerFile($app, $module, 'services', $service);
        mdump($fileName);
        include_once $fileName;
        $handler = new $className(self::$context);
        //Manager::$controllers[$className] = $handler;
        return $handler;
    }

    public static function getComponent($app, $module, $component)
    {
        Manager::logMessage("[MApp::getComponent  {$component}");
        $handler = new \Maestro\MVC\MComponent(self::$context);
        $fileName = self::getHandlerFile($app, $module, 'components', $component);
        include_once $fileName;
        $handler->component = new $component(self::$context);        
        return $handler;
    }
    
    public static function getModel($app, $module, $model, $data = null)
    {
        Manager::logMessage("[MApp::getModel  {$model}]");
        $fileName = self::getHandlerFile($app, $module, 'models', $model);
        include_once $fileName;
        Manager::import("{$module}\\models\\*");
        return new $model($data);
    }

    public static function getFiles($app, $module = '', $type = '')
    {
        $files = [];
        if ($module != '') {
            $array = self::$structure->$app->modules[$module]->$type;
        } else {
            $array = self::$structure->$app->$type;
        }
        $basePath = self::$structure->$app->basePath. DIRECTORY_SEPARATOR . $type;
        foreach($array as $handler => $path) {
            $files[$handler] = $basePath . DIRECTORY_SEPARATOR . $path;
        }
        return $files;
    }

    public static function terminate()
    {
        
    }

    public static function addConf()
    {
        $configFile = self::$path . '/conf/conf.php';
        Manager::loadConf($configFile);
    }

    public static function addMessages()
    {
        $msgDir = self::$path . '/conf/';
        Manager::$msg->addMessages($msgDir);
    }

    public static function addActions()
    {
        $actionsFile = self::$path . '/conf/actions.php';
        Manager::loadActions($actionsFile);
    }

    public static function addModuleConf($module)
    {
        $configFile = self::$path . '/modules/' . $module . '/conf/conf.php';
        Manager::loadConf($configFile);
    }

    public static function addModuleMessages($module)
    {
        $msgDir = self::$path . '/modules/' . $module . '/conf/';
        Manager::$msg->addMessages($msgDir);
    }

}

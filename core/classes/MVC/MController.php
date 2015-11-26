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

use Maestro\Manager,
    Maestro\Services\Exception\ENotFoundException;

/**
 * MController.
 * Classe responsável por executar a ação requisitada e definir a resposta enviada ao browser..
 */
class MController extends MHandler
{
    protected $controllerAction;
    protected $controller;
    protected $action;
    
    public function invoke()
    {
        $this->action = $this->context->getAction();
        $this->init();
        $this->dispatch($this->action);
    }

    public function getAction()
    {
        return $this->action;
    }

    public function init()
    {
        Manager::checkLogin();
    }

    public function dispatch($action)
    {
        mtrace('mcontroller::dispatch = ' . $action);
        if (!method_exists($this, $action)) {
            mtrace('action does not exists = ' . $action);
            try {
                $this->render($action);
            } catch (\Exception $e) {
                throw new ENotFoundException(_M("App: [%s], Module: [%s], Controller: [%s] : action [%s} not found!", [$this->application, $this->module, $this->name, $action]));
            }
        } else {
            try {
                $this->action = $action;
                if (Manager::getPage()->isPostBack()) {
                    $actionPost = $action . 'Post';
                    if (method_exists($this, $actionPost)) {
                        $action = $actionPost;
                    }
                }
                mtrace('executing = ' . $action);
                $method = new \ReflectionMethod(get_class($this), $action);
                $params = $method->getParameters();
                $values = array();
                foreach($params as $param){
                    $value = $this->data->{$param->getName()};
                    if(!$value && $param->isDefaultValueAvailable()){
                        $value = $param->getDefaultValue();
                    }
                    $values[] = $value;
                }
                $result = call_user_func_array([$this,$action],$values);
                if(!$this->getResult()){
                    $this->render($result);
                }
                //$this->$action();
            } catch (\Exception $e) {
                mdump($e->getMessage());
                if (Manager::PROD()) {
                    $this->renderPrompt('error', $e->getMessage());
                } else {
                    $this->renderPrompt('error', "[<b>" . $this->name . '/' . $action . "</b>]" . $e->getMessage());
                }
            }
        }
    }

    private function getContent($controller, $view, $parameters = NULL)
    {
        $app = $this->getApplication();
        $module = $this->getModule();
        $base = Manager::getAppPath('', $module, $app);
        $path = '/views/' . $controller . '/' . $view;
        $extensions = ['.xml','.php','.html','.js','.wiki'];
        foreach($extensions as $extension) {
            $fileName = $base . $path . $extension;
            if (file_exists($fileName)) {
                mtrace('MController::getContent ' . $fileName);
                $this->renderView($controller, $fileName, $parameters);
                break;
            }
        }
    }
    
    public function renderAppView($app, $module, $controller, $viewFile, $parameters)
    {
        $view = Manager::getView($app, $module, $controller, $viewFile);
        $view->setArgs($parameters);
        $view->process($this, $parameters);
    }

    public function renderView($controller, $viewFile, $parameters = array())
    {
        $this->renderAppView($this->application, $this->module, $controller, $viewFile, $parameters);
    }

    public function renderTemplate($templateName, $parameters = array())
    {
        $controller = strtolower($this->name);
        $path = Manager::getBasePath('/views/' . $controller . '/', $this->module);
        $file = $templateName . '.html';
        if (file_exists($path . '/' . $file)) {
            $template = new \Maestro\UI\MTemplate($path);
            $template->load($file);
            $this->getParameters($parameters);
            $object = (object) [
                        'template' => $template,
                        'parameters' => $this->data
            ];
            $this->setResult(new Results\MRenderTemplate($object));
        } else {
            throw new ENotFoundException(_M("Template [%s] was not found!", array($templateName)));
        }
    }

    public function renderPartial($viewName = '', $parameters = array())
    {
        if (($view = $viewName) == '') {
            $view = $this->action;
        }
        $this->getParameters($parameters);
        $controller = strtolower($this->name);
        $this->getContent($controller, $view, $this->data);
    }

    public function renderContent($viewName = '', $parameters = array())
    {
        $controller = strtolower($this->name);
        $view = $viewName;
        if ($view == '') {
            $view = $this->action;
        } else {
            $p = strpos($view, '/');
            if ($p !== false) {
                $controller = substr($view, 0, $p);
                $view = substr($view, $p + 1);
            }
        }
        $this->getParameters($parameters);
        $this->getContent($controller, $view, $this->data);
    }

    public function renderWindow($viewName = '', $parameters = array())
    {
        $this->renderContent($viewName, $parameters);
        $this->setResult(new Results\MBrowserWindow());
    }

    public function render($viewName = '', $parameters = array())
    {
        $this->renderContent($viewName, $parameters);
        $this->renderPage();
    }
    
    public function prepareFlush()
    {
        Manager::$response->prepareFlush();
    }

    public function flush($output)
    {
        Manager::$response->sendFlush($output);
    }

    public function renderFlush($viewName = '', $parameters = array())
    {
        Manager::getPage()->clearContent();
        $this->renderContent($viewName, $parameters);
        $output = Manager::getPage()->generate();
        $this->flush($output);
    }
    
}

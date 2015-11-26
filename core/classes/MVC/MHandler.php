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
 * MHandler.
 * Classe base dos diversos tipos de controllers (controller, service, component) 
 * que podem ser evocados por MApp::handler(). O objetivo é receber um "contexto"
 * e retornar um "resultado", após a evocação.
 */
class MHandler
{

    protected $context;
    protected $name;
    protected $application;
    protected $module;
    protected $data;
    protected $params;
    protected $result;
    protected $canCallHandler;
    protected $filters = [];
    public $renderArgs = array();

    public function __construct(\Maestro\MVC\MContext $context)
    {
        $this->context = $context;
        $this->name = $context->getHandler(); //$context->getController() . $context->getComponent() . $context->getService();
        $this->canCallHandler = true;
        $this->data = Manager::getData();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getApplication()
    {
        return $this->context->getApp();
    }

    public function getModule()
    {
        return $this->context->getModule();
    }

    public function forward($action)
    {
        MFrontController::setForward($action);
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    protected function setProperty($property, $value, $fields)
    {
        foreach ($fields as $field) {
            $this->data->{$field . $property} = $value;
        }
    }

    public function setData($dataArray)
    {
        MFrontController::setData($dataArray);
        $this->data = Manager::getData();
    }

    public function getParams()
    {
        return $this->params;
    }

    public function canCallHandler($status = true)
    {
        if (func_num_args()) {
            $this->canCallHandler = $status;
        } else {
            return $this->canCallHandler;
        }
    }

    public function execute()
    {
        // registra, carrega e processa os filtros 
        $filters = Manager::getConf('filters');
        if (is_array($filters)) {
            $appFilters = MApp::getFiles($this->getApplication(),'','filters');
            //Manager::addAutoloadPath(MApp::getPath() . '/filters');
            $this->filters = [];
            foreach ($filters as $filter) {
                include_once $appFilters[$filter];
                $filterClass = $filter . 'Filter';
                $this->filters[$filterClass] = new $filterClass($this);
                $this->filters[$filterClass]->preProcess();
            }
        }
        // se a execução não foi cancelada pelos filtros
        if ($this->canCallHandler()) {
            $this->invoke();
        }
        // executa o pos-processamento dos filtros indicados em conf.php
        foreach ($this->filters as $filter) {
            $filter->postProcess();
        }
    }

    protected function getParameters($parameters = NULL)
    {
        if (!(is_object($parameters) || is_array($parameters))) {
            $parameters = array('result' => $parameters);
        }
        foreach ($parameters as $name => $value) {
            $this->data->$name = $value;
        }
    }

    public function getService($service, $module = '')
    {
        return Manager::getService($this->getApplication(), ($module == '' ? $this->getModule() : $module), $service);
    }

    public function renderPrompt($prompt)
    {
        if (is_string($prompt)) {
            $args = func_get_args();
            $oPrompt = new \MPrompt(["type" => $prompt, "msg" => $args[1], "action1" => $args[2], "action2" => $args[3], "event1" => $args[4], "event2" => $args[5]]);
        } else {
            $oPrompt = $prompt;
        }
        $this->setResult(new Results\MRenderPage($oPrompt));
    }
    
    public function renderResponse($status, $message, $code = '000')
    {
        $response = (object) [
                    'status' => $status,
                    'message' => $message,
                    'code' => $code
        ];
        $this->setResult(new Results\MRenderJSON(json_encode($response)));
    }

    public function renderDialog($viewName = '', $parameters = array())
    {
        $this->renderContent($viewName, $parameters);
        Manager::getPage()->renderType = 'dialog';
        $this->setResult(new Results\MRenderJSON());
    }

    public function renderJSON($json = '')
    {
        if (!Manager::isAjaxCall()) {
            Manager::$ajax = new \Maestro\UI\MAjax(Manager::getOptions('charset'));
        }
        $ajax = Manager::getAjax();
        $ajax->setData($this->data);
        $this->setResult(new Results\MRenderJSON($json));
    }

    public function renderStream($stream)
    {
        $binary = (object) [
                    'stream' => $stream,
                    'inline' => true,
                    'fileName' => 'raw',
                    'filePath' => ''
        ];
        $this->setResult(new Results\MRenderBinary($binary));
    }

    public function renderBinary($stream, $fileName = '')
    {
        $binary = (object) [
                    'stream' => $stream,
                    'inline' => true,
                    'fileName' => $fileName,
                    'filePath' => ''
        ];
        $this->setResult(new Results\MRenderBinary($binary));
    }

    public function renderDownload($file, $fileName = '')
    {
        $binary = (object) [
                    'stream' => NULL,
                    'inline' => false,
                    'fileName' => $fileName,
                    'filePath' => $file
        ];
        $this->setResult(new Results\MRenderBinary($binary));
    }

    public function redirect($url)
    {
        $this->setResult(new Results\MRedirect($url));
    }

    public function notfound($msg)
    {
        $this->setResult(new Results\MNotFound($msg));
    }


    public function renderFile(\Manager\Types\MFile $file)
    {
        Manager::getPage()->window($file->getURL());
        $this->setResult(new Results\MBrowserFile($file));
    }

    public function renderPage()
    {
        $this->setResult(new Results\MRenderPage());
    }

}

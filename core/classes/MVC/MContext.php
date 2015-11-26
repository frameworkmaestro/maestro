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
    Maestro\Services\Exception\EMException,
    Maestro\Services\Exception\ENotFoundException;

/**
 * Contexto de execução de uma requisição.
 * O contexto é instanciado em MApp::contextualize().
 * 
 * A URL do Maestro tem o seguinte formato:
 * http://host.domain[:port]/(path/)(index.php)(app/)[(module/)](type/)(action/)(id/)?querystring.
 * onde 'type' indica a classe de um handler (controller | component | service)
 * e 'action' indica um método do handler (controller | component | service)
 * 
 * Os arquivos são acessados diretamente, já que o Maestro deve ser instalado
 * em uma pasta acessível pelo servidor web.
 */
class MContext
{

    /**
     * Indice se o acesso é feito à aplicação Core.
     * @var boolean
     */
    public $isCore;

    /**
     * Aplicação em execução.
     * @var string
     */
    public $app;

    /**
     * Módulo em execução.
     * @var string
     */
    public $module;

    /**
     * Tipo do handler em execução.
     * @var string
     */
    public $type;

    /**
     * Handler em execução.
     * @var string
     */
    public $handler;

    /**
     * Controller em execução.
     * @var string
     */
    public $controller;

    /**
     * Componente em execução.
     * @var string
     */
    public $component;

    /**
     * Serviço em execução.
     * @var string
     */
    public $service;

    /**
     * Ação em execução.
     * @var string
     */
    public $action;

    /**
     * Variável "id", se ela existir na URL.
     * @var string
     */
    public $id;

    /**
     * Variáveis passadas via querystring.
     * @var <type>
     */
    public $vars;

    /**
     * Objeto MRequest, com dados da requisição.
     * @var MRequest
     */
    public $request;

    /**
     * URL da requisição.
     * @var string
     */
    private $url;

    /**
     * Partes componentes do path.
     * @var array 
     */
    private $pathParts;

    /**
     * Path base da aplicação em execução.
     * @var string 
     */
    public $appPath;

    public function __construct($request)
    {
        $this->isCore = false;
        if (is_string($request)) {
            $path = $request;
            $this->url = $path;
        } else {
            $this->request = $request;
            if ($this->request->querystring != '') {
                parse_str($this->request->querystring, $this->vars);
            }
            $path = $this->request->getPathInfo();
            $this->url = $this->request->path;
        }
        mtrace('Context path: ' . $path);
        $this->pathParts = explode('/', $path);
        $app = array_shift($this->pathParts);
        if ($app != '') {
            $basePath = Manager::getAbsolutePath() . DIRECTORY_SEPARATOR;
            if ($app == 'core') {
                $this->isCore = true;
                $app = array_shift($this->pathParts);
                $this->appPath = $basePath . 'core' . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . $app;
            } else {
                $this->appPath = $basePath . 'apps' . DIRECTORY_SEPARATOR . $app;
            }
            $this->app = $app;
        } else {
            $this->app = Manager::getOptions('startup');
            $this->type = 'controller';
            $this->handler = 'main';
        }
    }

    public function defineContext($appStructure)
    {
        if ($this->handler == '') {
            $part = array_shift($this->pathParts);
            // verifica se $part é nome de um módulo
            if ($appStructure->modules[$part]) {
                $this->module = $part;
                $appStructure = $appStructure->modules[$part];
                $part = array_shift($this->pathParts);
                $path = $this->appPath . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->module;
            } else {
                $this->module = '';
                $path = $this->appPath;
            }
            $handler = '';
            $token = $part;
            // verifica se $part é nome de um handler (controller/component/service) ou de um serviço
            if (($part == 'api') || ($part == 'service')) {
                $part = array_shift($this->pathParts);
                if ($appStructure->services[$part]) {
                    $this->type = 'service';
                    $handler = $part;
                    $part = array_shift($this->pathParts);
                }                
            }
            while ($part && ($handler == '')) {
                if ($appStructure->controllers[$part]) {
                    $this->type = 'controller';
                    $handler = $part;
                    $part = array_shift($this->pathParts);
                } else {
                    if ($appStructure->services[$part]) {
                        $this->type = 'service';
                        $handler = $part;
                        $part = array_shift($this->pathParts);
                    } else {
                        if ($appStructure->components[$part]) {
                            $this->type = 'component';
                            $handler = $part;
                            $part = array_shift($this->pathParts);
                        } else {
                            $part = array_shift($this->pathParts);
                        }
                    }
                }
            }
            if ($handler) {
                $this->handler = $handler;
            } else {
                throw new ENotFoundException(_M("App: [%s], Module: [%s], Token: [%s] : Not found!", array($this->app, $this->module, $token)));
            }

            $this->action = ($part ? : ($this->type != 'component' ? 'main' : ''));
            $currentToken = 1 + ($this->module ? 1 : 0);
            if ($n = count($this->pathParts)) {
                for ($i = 0; $i < $n; $i++) {
                    $actionTokens[$i + 2] = $this->vars[$i] = $this->pathParts[$i];
                }
            }
            $this->id = $actionTokens[2];
            if ($this->id != '') {
                $_REQUEST['id'] = $this->id;
            }

            mtrace('Context app: ' . $this->app);
            mtrace('Context module: ' . $this->module);
            mtrace('Context type: ' . $this->type);
            mtrace('Context handler: ' . $this->handler);
            mtrace('Context action: ' . $this->action);
            mtrace('Context id: ' . $this->id);
        }
    }

    public function getURL()
    {
        return $this->url;
    }

    public function getAppPath()
    {
        return $this->appPath;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHandler()
    {
        return $this->handler;
    }
    /*
    public function getController()
    {
        return $this->controller;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getComponent()
    {
        return $this->component;
    }
    */
    public function getAction()
    {
        return $this->action;
    }

    public function getControllerAction()
    {
        return $this->controller . '.' . $this->action;
    }

    public function isCore()
    {
        return $this->isCore;
    }

    public function getNamespace($app, $module = '', $class = '', $type = 'controllers')
    {
        $ns = $this->isCore ? 'core\\' : '';
        $ns .= 'apps\\' . $app . '\\';
        $ns .= ($module ? 'modules\\' . $module . '\\' : '');
        $ns .= $type ? ($type . '\\') : '';
        $ns .= $class;
        return $ns;
    }

    public function get($name)
    {mdump($this->vars);
        return $this->vars[$name];
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function buildURL($action = '', $parameters = array())
    {
        $app = Manager::getApp();
        $module = Manager::getModule();
        if ($action{0} == '@') {
            $url = Manager::getAppURL($app);
            $action = substr($action, 1);
        } elseif ($action{0} == '>') {
            $url = Manager::getAppURL($app);
            $action = substr($action, 1);
        } elseif ($action{0} == '#') {
            $url = Manager::getStaticURL();
            $action = substr($action, 1);
        } else {
            $url = Manager::getAppURL($app);
        }
        $path = '';
        $parts = explode('/', $action);
        $i = 0;
        $n = count($parts);
        if ($parts[$i] == $app) {
            ++$i;
            --$n;
        }
        if ($n == 3) { //module
            $path = '/' . $parts[$i] . '/' . $parts[$i + 1] . '/' . $parts[$i + 2];
        } elseif ($n == 2) {
            $path = '/' . $parts[$i] . '/' . $parts[$i + 1];
        } elseif ($n == 1) {
            $path = '/' . $parts[$i];
        } else {
            throw new EMException(_M('Error building URL. Action = ' . $action));
        }
        if (count($parameters)) {
            $query = http_build_query($parameters);
            $path .= ((strpos($path, '?') === false) ? '?' : '') . $query;
        }
        $url .= $path;
        return $url;
    }

}

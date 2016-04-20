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
 * Controla o processamento da requisição.
 * Complete Class Description.
 */
class MFrontController
{

    static private $instance = NULL;
    static public $context;
    static public $result;
    static public $startup;
    static public $forward;
    static public $canCallHandler;
    static public $controller;
    static public $controllerAction;
    static public $filters;

    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new MFrontController();
        }
        return self::$instance;
    }

    static public function handlerRequest($data = NULL)
    {
        try {
            // se é uma chamada Ajax, inicializa MAjax
            if (Manager::isAjaxCall()) {
                Manager::$ajax = new \Maestro\UI\MAjax(Manager::getOptions('charset'));
            }
            MApp::contextualize();
            self::setData($data ? : $_REQUEST);
            mtrace('DTO Data:');
            mtrace(self::getData());
            self::init();
            do {
                self::$result = MApp::handler();
            } while (self::$forward != '');
            self::terminate();
        } catch (\Maestro\Services\Exception\ENotFoundException $e) {
            self::$result = new Results\MNotFound($e);
        } catch (\Maestro\Services\Exception\ESecurityException $e) {
            self::$result = new Results\MInternalError($e);
        } catch (\Maestro\Services\Exception\ETimeOutException $e) {
            self::$result = new Results\MInternalError($e);
        } catch (\Maestro\Services\Exception\ERuntimeException $e) {
            self::$result = new Results\MRunTimeError($e);
        } catch (\Maestro\Services\Exception\EMException $e) {
            self::$result = new Results\MInternalError($e);
        } catch (\Exception $e) {
            self::$result = new Results\MInternalError($e);
        }
    }

    public static function handlerResponse($return = false)
    {
        Manager::$session->freeze();
        return Manager::$response->sendResponse(self::$result, $return);
    }

    /**
     * Inicializa o venerável objeto $data.
     * @param array|object $value
     */
    public static function setData($value)
    {
        $data = new \stdClass();
        // se for o $_REQUEST, converte para objeto
        $valid = (is_object($value)) || (is_array($value) && count($value));
        if ($valid) {
            foreach ($value as $name => $value) {
                if ($name == '_') {
                    continue;
                }
                if (strpos($value, 'json:') === 0) {
                    $value = json_decode(substr($value, 5));
                }
                if (strpos($name, '_') !== false) {
                    list($obj, $name) = explode('_', $name);
                    $data->{$obj}->{$name} = $value;
                } else if (strpos($name, '::') !== false) {
                    list($obj, $name) = explode('::', $name);
                    $data->{$obj}->{$name} = $value;
                } else {
                    $data->{$name} = $value;
                }
            }
        }
        Manager::setData($data);
    }

    public static function getData()
    {
        return Manager::getData();
    }

    public static function setResult($result)
    {
        self::$result = $result;
    }

    public static function getResult()
    {
        return self::$result;
    }

    public static function getContext()
    {
        return MApp::getContext();
    }

    public static function getController()
    {
        return self::$controller;
    }

    public static function getAction()
    {
        return str_replace('.', '/', self::$controllerAction);
    }

    public static function setForward($action)
    {
        self::$forward = $action;
    }

    public static function getForward()
    {
        return self::$forward;
    }

    public static function init()
    {
        self::$forward = '';
        // registra alias para as classes do framework
        class_alias('Maestro\Manager', 'Manager');
        class_alias('Maestro\MVC\MBusinessModel','MBusinessModel');
        class_alias('Maestro\MVC\MController','MController');
        class_alias('Maestro\MVC\MApp','MApp');
        class_alias('Maestro\MVC\MService','MService');
        class_alias('Maestro\MVC\MFilter','MFilter');
        //class_alias('Maestro\Services\Exception\EControlException','EControlException');
        //Manager::import('Maestro\MVC\Results\*');
        class_alias('Maestro\UI\MBaseControl', 'MBaseControl');
        class_alias('Maestro\UI\MUI', 'MUI');
    }

    public static function terminate()
    {
    }
    
}

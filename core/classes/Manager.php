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

namespace Maestro;

use Nette,
    Tracy,
    Maestro\Services\HTTP\MRequest,
    Maestro\Services\HTTP\MResponse,
    Maestro\Services\MSession,
    Maestro\Services\MLogger,
    Maestro\Services\MTrace,
    Maestro\Services\MMessages,
    Maestro\Utils\MUtil,
    Maestro\UI\MPage,
    Maestro\MVC\MFrontController,
    Maestro\MVC\MApp,
    Maestro\MVC\MView;

/**
 * Classe principal do framework.
 * Manager implementa o padrão facade para várias classes utilitárias e serviços do framework.
 * 
 * @category    Maestro
 * @package     Core
 * @version     2.0 
 * @since       1.0
 * @copyright  Copyright (c) 2003-2015 UFJF (http://www.ufjf.br)
 * @license    http://maestro.org.br
 */
define('MAESTRO_VERSION', 'Maestro 2.0');
define('MAESTRO_AUTHOR', 'Maestro Team');

/**
 * Constantes para direitos de acesso.
 */
define('A_ACCESS', 1);   // 000001
define('A_QUERY', 1);    // 000001
define('A_INSERT', 2);   // 000010
define('A_DELETE', 4);   // 000100
define('A_UPDATE', 8);   // 001000
define('A_EXECUTE', 15); // 001111
define('A_SYSTEM', 31);  // 011111
define('A_ADMIN', 31);   // 011111
define('A_DEVELOP', 32); // 100000

/**
 * Indices do array em actions.php
 */
define('ACTION_CAPTION', 0);
define('ACTION_PATH', 1);
define('ACTION_ICON', 2);
define('ACTION_TRANSACTION', 3);
define('ACTION_ACCESS', 4);
define('ACTION_ACTIONS', 5);
define('ACTION_GROUP', 6);

/**
 * Constantes para estilos de fetch
 */
define('FETCH_ASSOC', \PDO::FETCH_ASSOC);
define('FETCH_NUM', \PDO::FETCH_NUM);

class Manager extends Nette\Object
{
    /*
     * Extensão dos arquivos de código.
     */

    static private $fileExtension = '.php';
    /*
     * Caracter separador dos namespaces.
     */
    static private $namespaceSeparator = '\\';
    /*
     * Instância singleton.
     */
    static private $instance = NULL;
    /*
     * Array de configurações conf/conf.php.
     */
    static private $conf = array();
    /*
     * Array de ações conf/actions.php.
     */
    public static $actions = array();
    /*
     * Objeto com dados de login.
     */
    public static $login;
    /*
     * Mensagens definidas.
     */
    public static $msg;
    /*
     * Indica se a chamada é Ajax ou não.
     */
    public static $ajax;
    /*
     * Array com paths registrados para autoload.
     */
    static protected $autoloadPaths = array();
    /*
     * Array com paths dos namespace registrados.
     */
    static protected $namespacePaths = array();
    /*
     * Versão atual
     */
    public static $_version;
    /*
     * Autor.
     */
    public static $_author;
    /*
     * Path da instalação do Framework.
     */
    public static $basePath;
    /*
     * Path da aplicação corrente (sendo executada).
     */
    public static $appPath;
    /*
     * Path do arquivo de configuração do Framework (conf.php).
     */
    public static $confPath;
    /*
     * Path para armazenamento de arquivo com acesso público.
     */
    public static $publicPath;
    /*
     * Path das aplicações instaladas.
     */
    public static $appsPath;
    /*
     * Path dos arquivos de classes do Framework.
     */
    public static $classPath;
    /*
     * Path do tema em uso.
     */
    public static $themePath;
    /*
     * `Path da aplicações Core.
     */
    public static $coreAppsPath;
    /*
     * Path do arquivo de configuração em uso (default: conf.php)
     */
    public static $configFile;
    /*
     * Array com as classes já carregadas pelo Maestro.
     */
    public static $autoload = array();
    /*
     * Objeto com dados do contexto de execução.
     */
    public static $context;
    /*
     * Indica se o JavaBridge está em uso.
     */
    public static $java;
    /*
     * Objeto do contexto Java (usado com JavaBridge).
     */
    public static $javaContext = NULL;
    /*
     * Objeto do contexto do Servlet Java (usado com JavaBridge).
     */
    public static $javaServletContext = NULL;
    /*
     * Objeto Cache.
     */
    public static $cache;
    /*
     * Nome da Aplicação (ou Módulo) de Admnistração do Framework.
     */
    public static $mad;
    /*
     * Array com as classes importadas. 
     */
    public static $import = array();
    /*
     * Array com alias para classes (uso com namespaces).
     */
    public static $classAlias = array();
    /*
     * Nome da aplicação sendo executada.
     */
    public static $app;
    /*
     * FrontController que processa a requisição.
     */
    public static $controller;
    /*
     * View sendo executada.
     */
    public static $view;
    /*
     * Modo de execução: Produção (PROD) ou Desenvolvimento (DEV)
     */
    public static $mode;
    /*
     * Objeto MPage.
     */
    public static $page;
    /*
     * Nome do tema em uso.
     */
    public static $theme;
    /*
     * URL base para acesso ao Framework.
     */
    public static $baseURL;
    /*
     * Objeto MDatabase que encapsula o acesso a banco de dados.
     */
    public static $db;
    /*
     * Array com cache dos Controllers já usados.
     */
    public static $controllers = array();
    /*
     * DTO (Data Transfer Object): objeto com dados da requisição,
     * transversal a todas as camadas.
     */
    public static $data;
    /*
     * Objeto MLogger.
     */
    public static $logger;
    /*
     * Objeto MTrace.
     */
    public static $trace;
    /*
     * Acesso ao objeto HTTP MRequest.
     */
    public static $request;
    /*
     * Acesso ao objeto MResponse.
     */
    public static $response;
    /*
     * Acesso ao objeto MSession.
     */
    public static $session;
    /*
     * Acesso ao objeto MAuth.
     */
    public static $auth;
    /*
     * Acesso ao objeto MPerms.
     */
    public static $perms;
    /*
     * Acesso ao objeto Painter.
     */
    public static $painter;

    /**
     * Construtor.
     * Construtor da classe Manager.
     */
    public function __construct()
    {
        
    }

    /**
     * Cria (se não existe) e retorna a instância singleton da class Manager.
     * @returns (object) Instance of Manager class
     */
    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new Manager();
        }
        return self::$instance;
    }

    /**
     * Processa uma requisição.
     * Método chamado pelo FrontPage (index.php) para processar uma requisição.
     * @param string $configFile Arquivo de configuração do Maestro
     * @param string $basePath Diretório onde o Maestro está instalado
     * @param string $app Aplicação a ser executada.
     */
    public static function process($configFile = '', $basePath = '', $app = '')
    {
        self::init($configFile, $basePath, $app);
        self::processRequest();
    }

    /**
     * Inicialização do Framework.
     * Método chamado para inicializar os atributos da classe Manager e registrar os AutoloadPaths.
     * @param string $configFile Arquivo de configuração do Maestro
     * @param string $basePath Diretório onde o Maestro está instalado
     * @param string $app Aplicação a ser executada.
     */
    public static function init($configFile = '', $basePath = '', $app = '')
    {
        self::$basePath = $basePath;
        self::$appsPath = $basePath . DIRECTORY_SEPARATOR . 'apps';
        self::$coreAppsPath = $basePath . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'apps';
        self::$app = $app;
        self::$confPath = $basePath . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'conf';
        self::$publicPath = $basePath . DIRECTORY_SEPARATOR . 'public';
        self::$classPath = $basePath . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'classes';
        // Carrega configurações 
        $managerConfigFile = self::$confPath . DIRECTORY_SEPARATOR . 'conf.php';
        self::loadConf($managerConfigFile);
        if ($configFile != $managerConfigFile) {
            // carrega configurações adicionais
            self::loadConf($managerConfigFile);
        }

        $debug = self::getConf('debug.enabled');
        if ($debug) {
            $mode = self::getConf('options.mode') == 'DEV' ? Tracy\Debugger::DEVELOPMENT : Tracy\Debugger::PRODUCTION;
            Tracy\Debugger::enable($mode, self::$basePath . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'log');
            error_reporting(self::getConf('debug.severity'));
            Tracy\Debugger::$logSeverity = self::getConf('debug.severity');
            Tracy\Debugger::$strictMode = self::getConf('debug.strictMode');
            Tracy\Debugger::$maxDepth = self::getConf('debug.maxDepth'); // default: 3
            Tracy\Debugger::$maxLen = self::getConf('debug.maxLen'); // default: 150            
        }
        self::$request = new MRequest;
        self::$response = new MResponse;
        // Maestro 1.0 compatibility
        self::addAutoloadClass('mlogin', self::$classPath . '/Security/MLoginMaestro1.php');
        // Session
        self::$session = new MSession(self::$request->request, self::$response);
        self::$session->init($_REQUEST['sid'] === '0' ? 0 : mrequest('sid'));
        self::$baseURL = self::$request->getBaseURL(false);

        Manager::logMessage('[RESET_LOG_MESSAGES]');
        if (self::$java = ($_SERVER["SERVER_SOFTWARE"] == "JavaBridge")) {
            require_once (self::$home . "/java/Java.inc");
            self::$javaContext = java_context();
            self::$javaServletContext = java_context()->getServletContext();
        }
        self::getLogin();
        self::$msg = new MMessages(self::getSession()->lang ? : self::getOptions('language'));
        self::$msg->loadMessages();
        self::$mode = self::getOptions("mode");
        date_default_timezone_set(self::getOptions("timezone"));
        setlocale(LC_ALL, self::getOptions("locale"));
        self::$mad = self::getConf('mad.module');
        register_shutdown_function("shutdown");
    }

    /**
     * Delega o processamento da requisição e a geração da resposta para o FrontController.
     * @param boolean $return Indica se a resposta será ecoada ou retornada ao chamador.
     * @return string
     */
    public static function processRequest($return = false)
    {
        MFrontController::handlerRequest();
        return MFrontController::handlerResponse($return);
    }

    /**
     * Método utilitário para geração de um path.
     * @param type $path
     * @param type $append
     */
    private static function getPath($path, $append = '')
    {
        return $path . ($append != '' ? '/' . $append : '');
    }

    /**
     * Retorna o path base do Framework.
     * @return string
     */
    public static function getHome()
    {
        return self::$basePath;
    }

    /**
     * Retorna o path absoluto para $relative.
     * 
     * @param string $relative 
     * @return string
     */
    public static function getAbsolutePath($relative = NULL)
    {
        return self::getPath(self::getHome(), $relative);
    }

    /**
     * Retorna o path da classe Core $className.
     * 
     * @param string $className 
     * @return string
     */
    public static function getClassPath($className = '')
    {
        return self::getPath(self::$classPath, $className);
    }

    /**
     * Retorna o path do arquivo de configuração $confFile.
     * 
     * @param string $confFile
     * @return string
     */
    public static function getConfPath($confFile = '')
    {
        return self::getPath(self::$confPath, $confFile);
    }

    /**
     * Retorna o path de um arquivo público.
     * @param type $app
     * @param type $module
     * @param type $file
     * @return string
     */
    public static function getPublicPath($app = '', $module = '', $file = '')
    {
        if ($app) {
            $path = self::$appsPath . '/' . $app . ($module ? '/modules/' . $module : '') . '/public';
        } else {
            $path = self::$publicPath;
        }
        return self::getPath($path, $file);
    }

    /**
     * Retorna o path do tema $theme.
     * @return string
     */
    public static function getThemePath($themeName = '')
    {
        $path = self::$themePath;
        if ($path == '') {
            $app = self::getConf('theme.app') ?: self::getApp();
            $path = self::getPublicPath($app, '', 'themes/' . self::getTheme());
            self::$themePath = $path;
        }
        return self::getPath($path, $themeName);
    }

    /**
     * Retorna o path do módulo/arquivo ($module/$file) na aplicação em execução. 
     * @return string
     */
    public static function getAppPath($file = '', $module = '', $app = '')
    {
        $path = self::getPath(self::$appsPath, $app ? : self::getApp() );
        if ($module) {
            $path .= '/modules/' . $module;
        }
        return self::getPath($path, $file);
    }

    /**
     * Retorna o path do módulo/arquivo ($module/$file) na aplicação em execução. 
     * @return string
     */
    public static function getModulePath($module, $file)
    {
        return self::getAppPath($file, $module);
    }

    /**
     * Retorna o path Core do Framework.
     * @return string
     */
    public static function getFrameworkPath($file = '')
    {
        return self::getPath(self::getHome() . '/core', $file);
    }

    /**
     * Retorna o path do arquivo $fileName na área var/files. $session indica se arquivo 
     * será acessível apenas durante a sessão em que foi criado.
     * @param string $fileName
     * @param boolean $session
     * @return string
     */
    public static function getFilesPath($fileName = '', $session = false)
    {
        $path = self::getHome() . '/core/var/files';
        if ($fileName != '') {
            if ($session) {
                $sid = self::getSession()->getId();
                $info = pathinfo($file);
                $fileName = md5(basename($file) . $sid) . ($info['extension'] ? '.' . $info['extension'] : '');
            }
            $path .= '/' . $fileName;
        }
        return $path;
    }

    /**
     * Adiciona path para classes que são carregadas via Autoloader.
     * @param string $includePath
     */
    public static function addAutoloadPath($includePath)
    {
        //mdump('addautoloadpath = ' . $includePath);
        //mtracestack();
        $path = realpath($includePath);
        $files = [];
        MUtil::dirToArray($path, $files);
        foreach ($files as $file) {
            self::addAutoloadClass(strtolower(basename($file, '.php')), $file);
        }
    }

    /**
     * Adiciona classe a ser carregada via Autoloader.
     * @param type $className Nome da classe.
     * @param type $classPath Path da classe.
     */
    public static function addAutoloadClass($className, $classPath)
    {
        if (file_exists($classPath)) {
            self::$autoload[$className] = $classPath;
        }
    }

    /**
     * Carrega uma classe.
     * @param string $className Nome da classe.
     * @return type
     */
    public static function autoload($className)
    {
        //mdump('autoload = ' . $className);
        $class = strtolower($className);
        $file = self::$autoload[$class];
        $include = '';
        if ($file != '') {
            if (file_exists($file)) {
                $include = $file;
            } else {
                $file = self::getClassPath($file);
                if (file_exists($file)) {
                    $include = $file;
                }
            }
        }
        if ($include != '') {
            include_once($include);
            self::$autoload[$class] = $include;
        } else {
            $ignoredPrefix = Manager::getConf('autoload.ignore');
            foreach ($ignoredPrefix as $ignored) {
                //startsWith
                if (strrpos($className, $ignored, -strlen($className)) !== FALSE) {
                    return;
                }
            }
            mdump('autoload creating control : ' . $className);
            //mtracestack();
            $controlClass = create_function('', 'class ' . $className . ' extends \MControl {}');
            $controlClass();
        }
    }

    /**
     * Verifica a existência do path $relative, relativo a $base. Retorna o path conforme existe no filesystem.
     * @param string $base Path base.
     * @param string $relative Path relativo à $base.
     * @return string Path conforme existe no filesystem.
     */
    public static function pathExists($base, $relative)
    {
        $scandir = scandir($base) ? : [];
        $found = false;
        foreach ($scandir as $path) {
            $found = (strcasecmp($relative, $path) == 0);
            if ($found) {
                break;
            }
        }
        return ($found ? $path : '');
    }

    /**
     * Registra o path de uma classe (ou conjunto de classes) para posterior importação.
     * "import" não carrega a classe: apenas registra o path em self::autoload, para o "autoload" carregar a classe quando ela for efetivamente instanciada.
     * @param string $path Path no formato x\y\z
     * @param string $className Nome da classe
     * @return string Retorna o path registrado para a classe.
     */
    public static function import($namespace, $classAlias = '')
    {
        mdump('import namespace = ' . $namespace);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $tokens = explode(DIRECTORY_SEPARATOR, $path);
        $wildcard = (end($tokens) == '*');
        if ($wildcard) {
            $path = substr($path, 0, -2);
            $registerAlias = true;
        }
        // verifica se o primeiro token é a app ou um módulo, e obtem o path completo
        $structure = MApp::getStructure(self::getApp());
        if ($structure->modules[$tokens[0]]) {
            $basePath = MApp::getPathModules() . DIRECTORY_SEPARATOR;
        } else {
            $basePath = Manager::$appsPath . DIRECTORY_SEPARATOR;
        }
        $fullPath = $basePath . $path;
        //mdump($fullPath);
        // seja o wildcard *, seja um unico arquivo, normaliza no array $files
        //$fullPath = realpath($fullPath);
        $files = array();
        //$file = basename($fullPath);
        //$wildcard = ($file == '*');
        if ($wildcard) {
            MUtil::dirToArray($fullPath, $files);
            //    $registerAlias = true;
        } else {
            $fullPath .= '.php';
            if (file_exists($fullPath)) {
                $files[] = $fullPath;
            }
        }
        //mdump($files);
        if (count($files)) {
            $classOriginal = $classAlias;
            foreach ($files as $file) {
                $fileName = basename($file, '.php');
                //$classPath = $wildcard ? $file : $fullPath;
                $className = strtolower($classOriginal ? : $fileName);
                //self::$autoload[$className] = $classPath;
                $fileName = str_replace([$fullPath . DIRECTORY_SEPARATOR, '.php'], '', $file);
                $fileName = str_replace(DIRECTORY_SEPARATOR, '\\', $fileName);
                $nsClass = $wildcard ? str_replace('*', $fileName, $namespace) : $namespace;
                class_alias($nsClass, $className);
                //mdump('*************'.$className . ' - ' . $classPath . ' - ' . $nsClass);
                //mdump('*************'.$className . ' - ' . $nsClass);
            }
        }
        //mdump(self::$autoload);
        //mdump(self::$classAlias);
        mdump('import result = ' . $fullPath);
        return $fullPath;
    }

    /**
     * Carrega configurações a partir de um arquivo conf.php.
     * @param string $configFile
     */
    public static function loadConf($configFile)
    {
        $conf = require($configFile);
        self::$conf = Utils\MUtil::arrayMergeOverwrite(self::$conf, $conf);
    }

    /**
     * Carrega ações a partir de um arquivo actions.php.
     * @param string $actionsFile
     */
    public static function loadActions($actionsFile)
    {
        if (file_exists($actionsFile)) {
            $actions = require($actionsFile);
            self::$actions = MUtil::arrayMergeOverwrite(self::$actions, $actions);
        }
    }

    /**
     * Carrega definições para Autoload de classes.
     * @param string $autoloadFile
     */
    public static function loadAutoload($autoloadFile)
    {
        $autoload = require($autoloadFile);
        self::$autoload = array_merge(self::$autoload, $autoload);
    }

    /**
     * Retorna o nome a aplicação em execução.
     */
    public static function getApp()
    {
        return self::getContext()->app;
    }

    /**
     * Retorna o nome do módulo em execução.
     */
    public static function getModule()
    {
        return self::getContext()->module;
    }

    /**
     * Retorna o nome do controller em execução.
     */
    public static function getCurrentController()
    {
        return MFrontController::getController();
    }

    /**
     * Retorna o nome da action em execução.
     */
    public static function getCurrentAction()
    {
        return MFrontController::getAction();
    }

    /**
     * Retorna o objeto DTO (variável $data)
     */
    public static function getData($attribute = NULL)
    {
        $data = self::$data;
        if ($attribute != NULL) {
            $data = $data->$attribute;
        }
        return $data;
    }

    /**
     * Atualiza o objeto DTO (variável $data)
     */
    public static function setData($value)
    {
        self::$data = $value;
    }

    /**
     * Retorna o objeto MRequest.
     */
    public static function getRequest()
    {
        return self::$request;
    }

    /**
     * Retorna o objeto MResponse.
     */
    public static function getResponse()
    {
        return self::$response;
    }

    /**
     * Retorna o nome da aplicação/módulo de administração do Framework.
     * @return string
     */
    public static function getMAD()
    {
        return self::$mad;
    }

    /**
     * Retorna informação sobre o tipo da requisição.
     *
     * @return (bool) True se for uma chamada Ajax.
     */
    public static function isAjaxCall()
    {
        return self::$request->isAjax();
    }

    /**
     * Indica se a requisição usou o método POST.
     *
     * @return (bool) True se for POST.
     */
    public static function isPost()
    {
        return self::$request->isPostBack();
    }

    /**
     * Retorna informação sobre o tipo de requisição.
     *
     * @return (bool) True se for um download de arquivo.
     */
    public static function isDownload()
    {
        return self::$request->isDownload();
    }

    /**
     * Retorna True se o servidor está em modo de desenvolvimento.
     * @return type
     */
    public static function DEV()
    {
        return (self::getOptions('mode') == 'DEV');
    }

    /**
     * Retorna True se o servidor está em modo de produção.
     * @return type
     */
    public static function PROD()
    {
        return (self::getOptions('mode') == 'PROD');
    }

    /**
     * ErrorHandler padrão do Maestro.
     * @param type $errno
     * @param type $errstr
     * @param type $errfile
     * @param type $errline
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $codes = self::$instance->getConf('logs.errorCodes');
        if (in_array($errno, $codes)) {
            self::logMessage("[ERROR] [Code] $errno [Error] $errstr [File] $errfile [Line] $errline");
        }
    }

    /**
     * Obtém o valor de uma configuração.
     * @param string $key Chave no formato x.y.z
     * @return mixed
     */
    public static function getConf($key = '')
    {
        $k = explode('.', $key);
        $conf = self::$conf;
        foreach ($k as $token) {
            $conf = $conf[$token];
        }
        return $conf;
    }

    /**
     * Obtém array de ações configuradas em actions.php.
     * @param string $action Ação.
     * @return array
     */
    public static function getActions($action = '')
    {
        if ($action != '') {
            $actions = self::getAction($action);
            return $actions[ACTION_ACTIONS];
        } else {
            return self::$actions;
        }
    }

    /**
     * Obtém array de uma ação específica configurada em actions.php.
     * @param string $action Ação
     * @return array
     */
    public static function getAction($action)
    {
        $actions = self::$actions;
        $k = explode('.', $action);
        $actions = $actions[$k[0]];
        for ($i = 1; $i < count($k); $i++) {
            $actions = $actions[ACTION_ACTIONS][$k[$i]];
        }
        return $actions;
    }

    /**
     * Obtém o valor de uma opção específica.
     * @param string $key Chave no formato x.y.z
     * @return type
     */
    public static function getOptions($key)
    {
        return isset(self::$conf['options'][$key]) ? self::$conf['options'][$key] : '';
    }

    /**
     * Obtém o valor de um parâmetro especifico.
     * @param string $key Chave no formato x.y.z
     * @return type
     */
    public static function getParams($key)
    {
        return isset(self::$conf['params'][$key]) ? self::$conf['params'][$key] : '';
    }

    /**
     * Define dinamicamente o valor de uma configuração.
     * @param string $key Chave no formato x.y.z
     * @param type $value
     */
    public static function setConf($key, $value)
    {
        $k = explode('.', $key);
        $n = count($k);
        if ($n == 1) {
            self::$conf[$k[0]] = $value;
        } else if ($n == 2) {
            self::$conf[$k[0]][$k[1]] = $value;
        } else if ($n == 3) {
            self::$conf[$k[0]][$k[1]][$k[2]] = $value;
        } else if ($n == 4) {
            self::$conf[$k[0]][$k[1]][$k[2]][$k[3]] = $value;
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getContext()
    {
        return MApp::getContext();
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getSession()
    {
        return self::$session;
    }

    public function setSession($session)
    {
        self::$session = $session;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getAuth()
    {
        if (is_null(self::$auth)) {
            $className = self::getConf('login.auth');
            if ($className == NULL) {
                $className = "MAuthDB";
            }
            if (!(class_exists($className, true))) {
                self::import('modules\\' . self::$conf['login']['module'] . '\\classes\\' . $className, $className);
            }
            self::$auth = new $className();
        }
        return self::$auth;
    }

    public function setAuth($auth)
    {
        self::$auth = $auth;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getPerms()
    {
        if (is_null(self::$perms)) {
            $className = self::getConf('login.perms');
            if ($className) {
                if (!(class_exists($className, true))) {
                    self::import('modules\\' . self::$conf['login']['module'] . '\\classes\\' . $className, $className);
                }
                return self::$perms = new $className();
            }
        }
        return self::$perms;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getLogin()
    {
        return self::getAuth()->getLogin();
    }

    public static function isLogged()
    {
        return self::getAuth()->isLogged();
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function checkLogin($deny = true)
    {
        $login = self::getAuth()->checkLogin();
        if (!$login && $deny) {
            throw new ELoginException(_M('Login required!'));
        }
        return $login;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $trans (tipo) desc
     * @param $access (tipo) desc
     * @param $deny (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function checkAccess($trans, $access, $deny = false)
    {
        return self::getPerms()->checkAccess($trans, $access, $deny);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function isHostAllowed()
    {
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        $returnValue = false;

        foreach (self::getOptions('hosts.allow') as $h) {
            if ($REMOTE_ADDR == $h) {
                $returnValue = true;
            }

// Is it a interval of IP's?
            if ((strpos($h, '-') > 0) && (substr($h, 0, strrpos($h, '.')) == substr($REMOTE_ADDR, 0, strrpos($REMOTE_ADDR, '.')))) {
                list($firstIP, $lastIP) = explode('-', $h);
                $lastIP = substr($firstIP, 0, strrpos($firstIP, '.') + 1) . $lastIP;

                $remoteIP = substr($REMOTE_ADDR, strrpos($REMOTE_ADDR, '.') + 1, strlen($REMOTE_ADDR));
                $startIP = substr($firstIP, strrpos($firstIP, '.') + 1, strlen($firstIP));
                $endIP = substr($lastIP, strrpos($lastIP, '.') + 1, strlen($lastIP));

                if (($startIP < $remoteIP) && ($endIP > $remoteIP)) {
                    $returnValue = true;
                }
            }
        }

        foreach (self::getOptions('hosts.allow') as $h) {
            if ($REMOTE_ADDR == $h) {
                $returnValue = false;
            }
        }

        return $returnValue;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getPage()
    {
        if (is_null(self::$page)) {
            self::$page = new MPage;
        }
        return self::$page;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getAjax()
    {
        return self::$ajax;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getResult()
    {
        return MFrontController::getResult();
    }

    public static function getMessage($key, $parameters = array())
    {
        return self::$msg->get($key, $parameters);
    }

    public static function getBaseURL($absolute = false, $dispatcher = false)
    {
        return ($absolute ? self::$request->getBaseURL(true) : self::$baseURL) . ($dispatcher ? '/' . self::getOptions('dispatcher') : '');
    }

    public static function getDispatchURL($absolute = false)
    {
        return self::getBaseURL($absolute, true);
    }

    public static function getAppURL($app = '', $file = '', $absolute = false)
    {
        $app = ($app ? : self::getApp());
        $file = str_replace($app . '/', '', $file);
        return $appURL = self::getBaseURL($absolute) . '/' . self::getOptions('dispatcher') . '/' . $app . ($file ? '/' . $file : '');
    }

    public static function getStaticURL($app = '', $file = '', $absolute = false, $module = '')
    {
        $app = ($app ? : self::getApp());
        if ($module != '') {
            return self::getBaseURL($absolute) . '/apps' . '/' . $app . '/modules/' . $module . '/public' . ($file ? '/' . $file : '');
        } else {
            return self::getBaseURL($absolute) . '/apps' . '/' . $app . '/public' . ($file ? '/' . $file : '');
        }    
    }

    public static function getDownloadURL($controller = '', $file = '', $inline = false, $absolute = true)
    {
        return self::getAppURL('core/download', $controller . '/' . ($inline ? 'inline' : 'save') . '/' . $file, $absolute);
    }

    public static function getURL($action = 'main/main', $args = array())
    {
        if (strtoupper(substr($action, 0, 4)) == 'HTTP') {
            return $action;
        }
        $url = self::getContext()->buildURL($action, $args);
        return $url;
    }

    public static function getAbsoluteURL($rel, $module = NULL)
    {
        $url = self::getBaseURL(true);
        if ($module) {
            $url .= '/modules/' . $module;
        }
// prepend path separator if necessary
        if (substr($rel, 0, 1) != '/') {
            $url .= '/';
        }
        $url .= $rel;
        return $url;
    }

    public static function getThemeURL($file = '')
    {
        if ($file{0} != '/') {
            $file = '/' . $file;
        }
        $theme = self::getTheme();
        $app = self::getConf('theme.app') ?: self::getApp();
        $url = self::getAbsoluteURL("apps/{$app}/public/themes/{$theme}{$file}");
        return $url;
    }

    /**
     * Retorna a URL em execução.
     * Retorna o endereço URL da página sendo executada.
     *
     * @returns (string) URL address
     *
     */
    public static function getCurrentURL($parametrized = false)
    {
        $url = self::$request->getURL();
        if ($url == '') {
            $url = self::$baseURL . '/' . self::getConf('options.dispatcher');
        }
        if ($parametrized) {
            $url .= "?";
            foreach (Manager::getData() as $key => $value) {
                if ((strpos($key, "__") !== 0) && (strpos($key, "grid") !== 0)) {
                    $value = urlencode($value);
                    $url .= $key . "=" . $value . "&";
                }
            }
        }
        return $url;
    }

    public static function _REQUEST($vars, $from = 'ALL', $order = '')
    {
        if (is_array($vars)) {
            foreach ($vars as $v) {
                $values[$v] = self::_REQUEST($v, $from);
            }
            return $values;
        } else {
// Seek in all scope?
            if ($from == 'ALL') {
// search in REQUEST
                if (!isset($value)) {
                    $value = $_REQUEST["$vars"];
                }
// Not found in REQUEST? try GET or POST
// Order? Default is use the same order as defined in php.ini ("EGPCS")
                if (!isset($order)) {
                    $order = ini_get('variables_order');
                }
                if (!isset($value)) {
                    if (strpos($order, 'G') < strpos($order, 'P')) {
                        $value = $_GET["$vars"];
// If not found, search in post
                        if (!isset($value)) {
                            $value = $_POST["$vars"];
                        }
                    } else {
                        $value = $_POST["$vars"];
// If not found, search in get
                        if (!isset($value)) {
                            $value = $_GET["$vars"];
                        }
                    }
                }
// If we still didn't have the value
// let's try in the global scope
                if ((!isset($value) ) && ( ( strpos($vars, '[') ) === false)) {
                    $value = $_GLOBALS["$vars"];
                }
// If we still didn't has the value
// let's try in the session scope

                if (!isset($value)) {
                    $value = $_SESSION["$vars"];
                }
            } else if ($from == 'GET') {
                $value = $_GET["$vars"];
            } elseif ($from == 'POST') {
                $value = $_POST["$vars"];
            } elseif ($from == 'SESSION') {
                $value = $_SESSION["$vars"];
            } elseif ($from == 'REQUEST') {
                $value = $_REQUEST["$vars"];
            }
            return $value;
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function getSysTime($format = 'd/m/Y H:i:s')
    {
        return date($format);
    }

    public static function getSysDate($format = 'd/m/Y')
    {
        return date($format);
    }

    public static function date($value = '', $format = '')
    {
        $value = ($value != '') ? $value : self::getSysDate();
        return new \Maestro\Types\MDate($value, $format);
    }

    public static function timestamp($value = '', $format = '')
    {
        $value = ($value != '') ? $value : self::getSysDate();
        return new \Maestro\Types\MTimestamp($value, $format);
    }

    public static function currency($value)
    {
        return new \Maestro\Types\MCurrency($value);
    }

//
// Factories Methods
//     GetDatabase
//     GetBusiness
//     GetUI
//     GetTheme
//
    public static function getDatabase($conf = NULL, $user = NULL, $pass = NULL)
    {
        $conf = $conf ? : 'maestro';
        if (isset(self::$db[$conf])) {
            $db = self::$db[$conf];
        } else {
            try {
                $db = new Database\MDatabase($conf);
                self::$db[$conf] = $db;
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return $db;
    }

    public static function getModelMAD($name = 'main', $data = NULL)
    {
        $module = self::getConf('mad.module');
        $name = self::getConf('mad.' . $name);
        // obtém o conf.php do módulo
        MApp::addModuleConf($module);
        // obtém as mensagens do módulo
        MApp::addModuleMessages($module);
        self::addAutoloadPath(MApp::getPathModules() . "/{$module}/components");
        $app = self::getApp();
        return MApp::getModel($app, $module, $name, $data);
    }

    public static function getTheme()
    {
        return self::getConf('theme.name');
    }

    public static function getLocale()
    {
        return self::$conf['options']['locale'][0];
    }

    public static function getPainter()
    {
        if (is_null(self::$painter)) {
            self::$painter = new \Painter;
        }
        return self::$painter;
    }

    public static function setPainter($value)
    {
        self::$painter = $value;
    }

    public static function getControllers()
    {
        return self::$controllers;
    }

    public static function getView($app, $module, $controller, $view)
    {
        self::$view = new MView($app, $module, $controller, $view);
        self::$view->init();
        return self::$view;
    }

//
// Log, Trace
//
    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $logname (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function getLog()
    {
        if (is_null(self::$logger)) {
            self::$logger = new MLogger;
        }
        return self::$logger;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function isLogging()
    {
        return self::getLog()->isLogging();
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $sql (tipo) desc
     * @param $force (tipo) desc
     * @param $conf= (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function logSQL($sql, $force = false, $conf = '?')
    {
        self::getLog()->logSQL($sql, $force, $conf);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $error (tipo) desc
     * @param $conf (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function logError($error, $conf = 'maestro')
    {
        self::getLog()->logError($error, $conf);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function logMessage($msg, array $context = array())
    {
        return self::getLog()->logMessage($msg, $context);
    }

    public static function log($level, $message, array $context = array())
    {
        return self::getLog()->log($level, $message, $context);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function deprecate($msg)
    {
        self::logMessage('[DEPRECATED]' . $msg);
    }

    public static function getTrace()
    {
        if (is_null(self::$trace)) {
            self::$trace = new MTrace;
        }
        return self::$trace;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $msg (tipo) desc
     * @param $file (tipo) desc
     * @param $line=0 (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public static function trace($var, $file = false, $line = 0)
    {
        $msg = Tracy\Dumper::toText($var, array('truncate' => self::getConf('debug.maxLen'), 'depth' => self::getConf('debug.maxDepth')));
        return self::getTrace()->trace($msg, $file, $line);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function dump($msg, $file = false, $line = 0)
    {
        return self::getTrace()->dump($msg, $file, $line);
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public static function traceStack()
    {
        return self::getTrace()->traceStack();
    }

    public static function version()
    {
        return MAESTRO_VERSION;
    }

    public static function author()
    {
        return MAESTRO_AUTHOR;
    }

    /**
     * Send a file to client
     * @param string $module Module
     * @param string $filename Complete filepath relative to directory "files" on module dir
     */
    public static function saveFile($module = '', $filename = '', $dir = 'html/files/')
    {
        if (empty($filename)) {
            return false;
        }
        $path = self::getModulePath($module, $dir);
        self::$response->sendDownload($path . $filename);
    }

}

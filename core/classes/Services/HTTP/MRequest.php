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

namespace Maestro\Services\HTTP;

use Maestro,
    Maestro\Manager,
    Nette\Http\RequestFactory;

/**
 * MRequest.
 * Provê acesso aos dados da requisiçao feita via HTTP.
 */
class MRequest
{

    public $request;
    public $url;
    public $path;

    public function __construct()
    {
        $requestFactory = new RequestFactory;
        $requestFactory->setProxy(array());
        $this->request = $requestFactory->createHttpRequest();
        $this->url = $this->request->getUrl();
        $this->resolveFormat();
        $this->path = $this->url->getPathInfo();
        //mtrace('MRequest path = ' . $this->path);
        //mtrace('BaseURL = '.$this->url->getBaseURL());
        //mtrace('BasePath = '. $this->url->getBasePath());
        //mtrace('relative url = '. $this->url->getRelativeURL());
        
        /*

        $this->host = $_SERVER['SERVER_NAME'];
        $this->path = $this->getPathInfo();
        mtrace('MRequest path = ' . $this->path);
        $this->querystring = $this->getQueryString();
        $this->method = $this->getRequestType();
        $this->domain = $this->getServerName();
        $this->remoteAddress = $this->getUserHostAddress();
        $this->contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        $this->port = $this->getPort();
        $this->secure = $this->getIsSecureConnection();
        $this->headers = $_SERVER;
        $this->cookies = isset($_COOKIES) ? $_COOKIES : '';
        $dispatch = Manager::getOptions('dispatch');
        $this->baseUrl = $this->getBaseUrl();
        $this->date = Manager::getSysTime();
        $this->isNew = true;
        $this->user = '';
        $this->password = '';
        $this->isLoopback = ($this->remoteAddress == '127.0.0.1');
        $this->params = $_REQUEST;
        $this->url = $this->getUrl();
        $this->dispatch = $this->getBase() . $dispatch;


        $auth = isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : '';
        if (($auth != '') && (substr($auth, 0, 6) == "Basic ")) {
            $this->user = $_SERVER['PHP_AUTH_USER'];
            $this->password = $_SERVER['PHP_AUTH_PW'];
        }
         * 
         */
    }

    /**
     * Resolve o formato da requisição no header Accept
     * (nesta ordem : html > xml > json > text)
     */
    public function resolveFormat()
    {

        if ($this->format != null) {
            return;
        }
        $accept = $_SERVER['HTTP_ACCEPT'];
        if ($accept == '') {
            $this->format = "html";
            return;
        }
        if (strpos($accept, "application/xhtml") !== false || strpos($accept, "text/html") !== false || substr($accept, 0, 3) == "*/*") {
            $this->format = "html";
            return;
        }
        if (strpos($accept, "application/xml") !== false || strpos($accept, "text/xml") !== false) {
            $this->format = "xml";
            return;
        }
        if (strpos($accept, "text/plain") != false) {
            $this->format = "txt";
            return;
        }
        if (strpos($accept, "application/json") !== false || strpos($accept, "text/javascript") != false) {
            $this->format = "json";
            return;
        }
        if (substr($accept, 0, -3) == "*/*") {
            $this->format = "html";
            return;
        }
    }

    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * A rquisição foi feita via AJAX.
     * (rely on the X-Requested-With header).
     */
    public function isAjax()
    {
        return $this->request->isAjax();
    }

    public function isPostBack()
    {
        return $this->request->isPost();
    }

    public function isFileUpload()
    {
        return ($_REQUEST['__ISFILEUPLOAD'] == 'yes');
    }
    
    public function getPathInfo() {
        return $this->url->getPathInfo() ?: $_SERVER['REQUEST_PATH'];
    }

    public function getBaseURL($absolute = false) {
        $url = ($absolute ? $this->url->getBaseURL() : $this->url->getBasePath());
        $baseURL = str_replace('/' . Manager::getOptions('dispatcher') . '/','',$url);
        return $baseURL;
    }

    public function getURL() {
        return $this->url->getAbsoluteURL();
    }

}

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

use Maestro\Manager;

/**
 * MResponse.
 * Provê métodos para geração da resposta à requisiçao feita via HTTP.
 */
class MResponse extends \Nette\Http\Response
{

    private $mimeType = array(
        'ai' => 'application/postscript', 'aif' => 'audio/x-aiff',
        'aifc' => 'audio/x-aiff', 'aiff' => 'audio/x-aiff',
        'asf' => 'video/x-ms-asf', 'asr' => 'video/x-ms-asf',
        'asx' => 'video/x-ms-asf', 'au' => 'audio/basic',
        'avi' => 'video/x-msvideo', 'bin' => 'application/octet-stream',
        'bmp' => 'image/bmp', 'css' => 'text/css',
        'doc' => 'application/msword', 'gif' => 'image/gif',
        'gz' => 'application/x-gzip', 'hlp' => ' application/winhlp',
        'htm' => 'text/html', 'html' => 'text/html',
        'ico' => 'image/x-icon', 'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg',
        'js' => 'application/x-javascript', 'lzh' => 'application/octet-stream',
        'mid' => 'audio/mid', 'mov' => 'video/quicktime',
        'mp3' => 'audio/mpeg', 'mpa' => 'video/mpeg',
        'mpe' => 'video/mpeg', 'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg', 'pdf' => 'application/pdf',
        'png' => 'image/png', 'pps' => 'application/vnd.ms-powerpoint',
        'ppt' => 'application/vnd.ms-powerpoint', 'ps' => 'application/postscript',
        'qt' => 'video/quicktime', 'ra' => 'audio/x-pn-realaudio',
        'ram' => 'audio/x-pn-realaudio', 'rtf' => 'application/rtf',
        'snd' => 'audio/basic', 'tgz' => 'application/x-compressed',
        'tif' => 'image/tiff', 'tiff' => 'image/tiff',
        'txt' => 'text/plain', 'wav' => 'audio/x-wav',
        'xbm' => 'image/x-xbitmap', 'xpm' => 'image/x-xpixmap',
        'z' => 'application/x-compress', 'zip' => 'application/zip'
    );
    private $contentLength;
    private $contentDisposition;
    private $contentTransferEncoding;
    private $fileName;
    private $fileNameDown;
    private $baseName;
    private $alreadyFlushed = false;

    /**
     * Response status code
     */
    public $status = 200;

    /**
     * Response content type
     */
    public $contentType;

    /**
     * Response body stream
     */
    public $out;

    /**
     * Send this file directly
     */
    public $direct;

    public function __construct()
    {
        $this->contentType = "";
        $this->contentLength = "";
        $this->contentDisposition = "";
        $this->contentTransferEncoding = "";
        $this->fileName = "";
        $this->fileNameDown = "";
    }

    public function setContentTypeIfNotSet($contentType)
    {
        if ($this->contentType == '') {
            $this->contentType = $contentType;
        }
    }

    public function __down()
    {
        $this->contentType = "application/save";
        $this->contentLength = "";
        $this->contentDisposition = "";
        $this->contentTransferEncoding = "";
        $this->fileName = "";
        $this->fileNameDown = "";
    }

    public function _setContentLength()
    {
        $this->contentLength = filesize($this->fileName);
    }

    public function setContentLength($value)
    {
        $this->contentLength = $value;
    }

    function setContentDisposition($value)
    {
        $this->contentDisposition = $value;
    }

    public function setContentTransferEncoding($value)
    {
        $this->contentTransferEncoding = $value;
    }

    public function getMimeType($fileName)
    {
        $path_parts = pathinfo($fileName);
        $mime = $this->mimeType[$path_parts['extension']];
        $type = $mime ? $mime : "application/octet-stream";
        return $type;
    }

    /*
      Métodos Send.
     */

    /**
     * Principal método para enviar resposta para o browser.
     * Analisa o objeto $result e decide qual o método será usado para o envio da resposta.
     *
     * @param object $result Objeto obtido durante a execução da requisição.
     * @param boolean $return Indica se o método deve retornar a resposta ou enviá-la diretamente (via echo).
     * @return string Conteúdo da resposta gerada, no caso de $return = true.
     */
    public function sendResponse($result, $return = false)
    {
        if ($this->alreadyFlushed) {
            return;
        }
        if ($result == null) {
            return;
        }
        //$request = Manager::getRequest();
        //$response = $this;
        if ($result instanceof \Maestro\MVC\Results\MRenderBinary) {
            $this->sendStream($result);
        } else {
            $this->out = $result->getOutput();
            //$result->apply($request, $response);
            //foreach ($this->getHeaders() as $header) {
            //    $this->setHeader($header);
            //}
            if ($return) {
                return $this->out;
            }
            echo $this->out;
        }
    }

    public function sendStream($result)
    {
        $filePath = $result->getFilePath();
        if ($filePath != '') {
            if (file_exists($filePath)) {
                $fileName = $result->getFileName() ? : $this->baseName;
                $this->_setContentLength();
                header('Expires: 0');
                header('Pragma: public');
                header("Content-Type: " . $this->contentType);
                header("Content-Length: " . filesize($filePath));
                if ($result->getInline()) {
                    header("Content-Disposition: inline; filename=" . $fileName);
                } else {
                    header("Content-Disposition: attachment; filename=" . $fileName);
                }
                header("Cache-Control: cache"); // HTTP/1.1 
                header("Content-Transfer-Encoding: binary");

                $fp = fopen($filePath, "r");
                fpassthru($fp);
                fclose($fp);
            }
        } else {
            $fileName = $result->getFileName() ? : 'download';
            $stream = $result->getStream();
            if ($fileName != 'raw') {
                $this->contentLength = strlen($stream);
                header('Expires: 0');
                header('Pragma: public');
                header("Content-Type: " . $this->contentType);
                header("Content-Length: " . $this->contentLength);
                if ($result->getInline()) {
                    header("Content-Disposition: inline; filename=" . $fileName);
                } else {
                    header("Content-Disposition: attachment; filename=" . $fileName);
                }
                header("Cache-Control: cache"); // HTTP/1.1 
                header("Content-Transfer-Encoding: binary");
            }
            echo $stream;
        }
        exit;
    }

    public function prepareFlush()
    {
        $this->alreadyFlushed = true;
        header("Cache-Control: no-cache");
        for ($i = 0; $i < ob_get_level(); $i++) {
            ob_end_flush();
        }
        ob_implicit_flush(1);
        ob_start();
        echo str_repeat(" ", 1024), "\n";
    }

    public function sendFlush($output)
    {
        echo $output;
        ob_end_flush();
        ob_flush();
        flush();
    }

}

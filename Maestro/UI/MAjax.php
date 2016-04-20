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

/**
 * MAjax.
 * Tratamento das respostas às requisições Ajax. Define um objeto base (que pode 
 * ser composto por outros objetos) e gera a resposta (Texto, XML ou JSON) a partir
 * deste objeto.
 */

namespace Maestro\UI;

use Maestro\Manager;

class MAjax extends MBase
{

    /**
     * Versão do XML. 
     * @var string
     */
    public $version = '1.0';

    /**
     * Tipo da resposta (TXT, HTML, JSON, OBJECT, E4X, XML).
     * @var string
     */
    public $responseType;

    /**
     * Array com objetos internos.
     * @var array
     */
    public $composites = array();

    /**
     * Define a codificação de caracteres usada na geração da resposta.
     * @var string
     */
    public $inputEncoding;

    public function __construct($inputEncoding = 'UTF-8')
    {
        parent::__construct();
        $this->setName('ajaxResponse');
        $this->setId('');
        $this->setEncoding($inputEncoding);
        // por default, o tipo de resposta é aquele solicitado na requisição HTTP
        $this->setResponseType(Manager::getRequest()->getFormat());
    }

    /**
     * Retorna a resposta formatada de acordo com o tipo definido em $responseType.
     * @return mixed
     */
    public function returnData()
    {
        $charset = MAjaxTransformer::findOutputCharset($this->getEncoding());
        switch ($this->responseType) {

            case 'TXT':
            case 'HTML':
                header('Content-type: text/plain; charset=' . $charset);
                $data = MAjaxTransformer::toString($this);
                return $data;
                break;

            case 'JSON':
            case 'OBJECT':
                $data = MAjaxTransformer::toJSON($this);
                //$header = 'Content-type: text/plain; ';
                $header = 'Content-type: application/json; ';
                if (Manager::getPage()->fileUpload) {
                    $newdata = "{\"base64\":\"" . base64_encode($data) . "\"}";
                    $data = "<html><body><textarea>$newdata</textarea></body></html>";
                    $header = 'Content-type: text/html; ';
                }
                header($header . 'charset=' . $charset);
                return $data;
                break;

            case 'E4X':
            case 'XML':
                header('Content-type:  text/xml; charset=' . $charset);
                $data = '<?xml version="1.0" encoding="' . $charset . '"?>'
                        . MAjaxTransformer::toXML($this);
                return $data;
                break;

            default:
                return 'ERROR: invalid response type \'' . $this->responseType . '\'';
        }
    }

    /**
     * Retorna a resposta JSON, quando os dados em $this->base->data já foram definidos neste formato.
     * @return string
     */
    public function returnJSON()
    {
        $data = $this->getData();
        $header = 'Content-type: application/json; ';
        header($header . 'charset=UTF-8');
        return $data;
    }

    public function setEncoding($encoding)
    {
        $this->inputEncoding = strtoupper((string) $encoding);
    }

    public function getEncoding()
    {
        return $this->inputEncoding;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function addNode($nodeName, $id = '')
    {
        $composites = count($this->composites);
        $this->composites[$composites] = new MAjax($this->inputEncoding);
        $this->composites[$composites]->setName($nodename);
        $this->composites[$composites]->setAttribute('id', $id);
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function setResponseType($value)
    {
        if (isset($value)) {
            $this->responseType = htmlentities(strip_tags(strtoupper((string) $value)));
        }
    }

    public function isEmpty()
    {
        return (count($this->composites) == 0) && ($this->data == '');
    }

}

class MAjaxTransformer
{

    static public function toString($node)
    {
        $returnValue = '';
        foreach ($node->composites as $composite) {
            $returnValue .= MAjaxTransformer::toString($composite);
        }
        $returnValue .= MAjaxTransformer::encode($node->getData(), $node->getEncoding());
        return $returnValue;
    }

    static public function toXML($node)
    {
        $returnValue = '<' . $node->getName();
        // handle attributes
        foreach ($node->attributes as $name => $value) {
            if ($value != '') {
                $returnValue .= ' ' . $name . '="' . $node->getAttribute($name) . '"';
            }
        }
        $returnValue .= '>';
        // handle subnodes
        foreach ($node->composites as $composite) {
            $returnValue .= MAjaxTransformer::toXML($composite);
        }
        $returnValue .= MAjaxTransformer::encode($node->getData(), $node->getEncoding())
                . '</' . $node->get_name() . '>';

        return $returnValue;
    }

    static public function toJSON($node)
    {
        $returnValue = '';
        $JSON_node = new \stdClass();
        // handle subnodes
        foreach ($node->composites as $composite) {
            if (!is_array($JSON_node->{$composite->nodeName})) {
                $JSON_node->{$composite->nodeName} = array();
            }
            $JSON_node->{$composite->nodeName}[] = $composite->nodes;
        }
        if ($id = $node->getId()) {
            $JSON_node->id = $id;
        }
        if ($type = $node->getType()) {
            $JSON_node->type = $type;
        }
        if ($data = $node->getData()) {
            $JSON_node->data = $data;
        }
        $returnValue = \Maestro\Services\MJSON::encode($JSON_node);
        return $returnValue;
    }

    static public function detectUTF8($string)
    {
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
    }

    static public function encode($data, $encoding)
    {
        if (MAjaxTransformer::detectUTF8($data)) {
            // if UTF-8 data was supplied everything is fine!
            $returnValue = $data;
        } elseif (function_exists('iconv')) {
            // iconv is by far the most flexible approach, try this first
            $returnValue = iconv($encoding, 'UTF-8', $data);
        } elseif ($encoding == 'ISO-8859-1') {
            // for ISO-8859-1 we can use utf8-encode()
            $returnValue = utf8_encode($data);
        } else {
            // give up. if UTF-8 data was supplied everything is fine!
            $returnValue = $data;
        } /* end: if */

        return $returnValue;
    }

    static public function decode($data, $encoding)
    {
        // convert string

        if (is_string($data)) {
            if (!MAjaxTransformer::detectUTF8($data)) {
                $returnValue = $data;
            } elseif (function_exists('iconv')) {
                // iconv is by far the most flexible approach, try this first
                $returnValue = iconv('UTF-8', $encoding, $data);
            } elseif ($encoding == 'ISO-8859-1') {
                // for ISO-8859-1 we can use utf8-decode()
                $returnValue = utf8_decode($data);
            } else {
                // give up. if data was supplied in the correct format everything is fine!
                $returnValue = $data;
            } // end: if
        } else {
            // non-string value
            $returnValue = $data;
        } // end: if

        return $returnValue;
    }

    /**
     * decodes a (nested) array of data from UTF-8 into the configured character set
     *
     * @access   public
     * @param    array     $data         data to convert
     * @param    string    $encoding     character encoding
     * @return   array
     */
    static public function decodeArray($data, $encoding)
    {
        $returnValue = array();

        foreach ($data as $key => $value) {

            if (!is_array($value)) {
                $returnValue[$key] = MAjaxTransformer::decode($value, $encoding);
            } else {
                $returnValue[$key] = MAjaxTransformer::decode_array($value, $encoding);
            }
        }

        return $returnValue;
    }

    /**
     * Determina o conjunto de caracters da saída, baseado no conjunto de caracteres da entrada.
     *
     * @param    string    $encoding     character encoding
     * @return   string
     */
    static public function findOutputCharset($encoding)
    {
        $returnValue = 'UTF-8';
        if (function_exists('iconv') || $encoding == 'UTF-8' || $encoding == 'ISO-8859-1') {

            $returnValue = 'UTF-8';
        } else {
            $returnValue = $encoding;
        }
        return $returnValue;
    }

}

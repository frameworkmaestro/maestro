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


class MModel
{

    public function __construct($data = NULL)
    {
    }

    /**
     * Retorna um ValueObject com atributos com valores planos (tipo simples).
     * @return \stdClass
     */
    public function getData()
    {
        $data = new \stdClass();
        $a = \Maestro\Utils\MUtil::getClassProperties(get_class($this),'public, private, protected');
        mdump($a);
        $attributes = get_object_vars($this);
        mdump('-=-=-=');
        mdump($attributes);
        foreach ($attributes as $attribute) {
            $method = 'get' . $attribute;
            mdump('method = ' . $method);
            $methodExists = method_exists($this, $method);
            if ($methodExists) {
                $rawValue = $this->$method();
                if (isset($rawValue)) {
                    mdump($attribute);
                    mdump($rawValue);
                    if (is_object($rawValue)) {
                        $value = $rawValue->getPlainValue();
                    } else {
                        $value = $rawValue;
                    }
                    $data->$attribute = $value;
                }
            }
        }
        return $data;
    }

    /**
     * Recebe um ValueObject com valores planos e inicializa os atributos do Model.
     * @param object $data
     */
    public function setData($data)
    {
        if (is_null($data)) {
            return;
        }
        $attributes = get_class_vars(__CLASS__);
        foreach ($attributes as $attribute) {
            $method = 'set' . $attribute;
            if (method_exists($this, $method)) {
                $valid = false;
                if (isset($data->$attribute)) {
                    $value = $data->$attribute;
                    $valid = true;
                } elseif (is_array($data) && isset($data[$attribute])) {
                    $value = $data[$attribute];
                    $valid = true;
                }
                if ($valid) {
                    $this->$method($value);
                }
            }
        }
    }

}


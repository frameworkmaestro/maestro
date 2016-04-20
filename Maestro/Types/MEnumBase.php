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
namespace Maestro\Types;

use Maestro\Manager;

/**
 * Classe base para Enumerações (equivalente ao Enum do C#)
 */
abstract class MEnumBase {

    const SEPARATOR = ' ';

    /**
     * Retorna a lista de constantes da classe filha à classe EnumBase
     * @return type 
     */
    public static function listAll() {
        $oClass = new \ReflectionClass(get_called_class());
        $constants = $oClass->getConstants();
        unset($constants['SEPARATOR']);
        $values = str_replace('_', static::SEPARATOR, array_keys($constants));
        return array_combine(array_values($constants), $values);
    }

    /**
     * Retorna uma lista de constantes específica da classe filha à classe EnumBase
     * @return type 
     */
    public static function listByValues($arrayValues) {
        $allValues = self::listAll();
        if (is_array($arrayValues)) {
            foreach ($arrayValues as $value) {
                if (isset($allValues[$value])) {
                    $result[$value] = $allValues[$value];
                }
            }
        }
        return $result;
    }

    /**
     * Retorna a string relacionada ao valor da constante
     */
    public static function getById($id) {
        $constants = self::listAll();
        return $constants[$id];
    }

    /**
     * Retorna a constante dado
     */
    public static function getConstById($id) {
        $oClass = new \ReflectionClass(get_called_class());
        $constants = $oClass->getConstants();
        foreach ($constants as $key => $value) {
            if ($value == $id) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Indica se o parâmetro é um valor válido para a Enum
     */
    public static function isValid($value) {
        $oClass = new \ReflectionClass(get_called_class());
        $constants = $oClass->getConstants();
        $valid = false;
        foreach ($constants as $key => $constant) {
            if ($value == $constant) {
                $valid = true;
                break;
            }
        }
        return $valid;
    }

    /**
     * Cada enum deve dizer qual é o seu default
     */
    public function getDefault() {
        return '';
    }

}

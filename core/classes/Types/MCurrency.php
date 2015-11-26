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
 * Classe utilitária para trabalhar com valores monetários.
 * Métodos para formatar e validar strings representando valores monetários.
 * 
 * @category    Maestro
 * @package     Core
 * @subpackage  Types
 * @version     1.0 
 * @since       1.0
 */
class MCurrency extends MType {

    private $value;

    public function __construct($value) {
        $this->setValue($value);
    }

    private function getValueFromString($value){
        $l = localeConv();
        $sign = (strpos($value,$l['negative_sign']) !== false) ? -1 : 1;
        $value = strtr($value,$l['positive_sign'].$l['negative_sign'].'()', '    ');
        $value = str_replace(' ', '', $value);
        $value = str_replace($l['currency_symbol'], '', $value);
        $value = str_replace($l['mon_thousands_sep'], '', $value);
        $value = str_replace($l['mon_decimal_point'], '.', $value);
        return (float) ($value * $sign);
    }

    public function getValue() {
        return $this->value ? : (float) 0.0;
    }

    public function setValue($value) {
        if ($value instanceof MCurrency){
            $value = $value->getValue();
        }
        $this->value = (is_numeric($value) ? round($value, 2,PHP_ROUND_HALF_DOWN) : (is_string($value) ? $this->getValueFromString($value) : 0.0)) ;
    }

    public function format() {
        $l = localeConv();
        // Sign specifications:
        if ($this->value >= 0) {
            $sign = $l['positive_sign'];
            $sign_posn = $l['p_sign_posn'];
            $sep_by_space = $l['p_sep_by_space'];
            $cs_precedes = $l['p_cs_precedes'];
        } else {
            $sign = $l['negative_sign'];
            $sign_posn = $l['n_sign_posn'];
            $sep_by_space = $l['n_sep_by_space'];
            $cs_precedes = $l['n_cs_precedes'];
        }
        // Currency format:
        $m = number_format(abs($this->value), $l['frac_digits'], $l['mon_decimal_point'], $l['mon_thousands_sep']);
        if ($sep_by_space) {
            $space = ' ';
        } else {
            $space = '';
        }
        if ($cs_precedes) {
            $m = $l['currency_symbol'] . $space . $m;
        } else {
            $m = $m . $space . $l['currency_symbol'];
        }
        switch ($sign_posn) {
            case 0: $m = "($m)";
                break;
            case 1: $m = "$sign$m";
                break;
            case 2: $m = "$m$sign";
                break;
            case 3: $m = "$sign$m";
                break;
            case 4: $m = "$m$sign";
                break;
            default: $m = "$m [error sign_posn=$sign_posn&nbsp;!]";
        }
        return $m;
    }

    public function formatValue() {
        $l = localeConv();
        // Sign specifications:
        if ($this->value >= 0) {
            $sign = $l['positive_sign'];
            $sign_posn = $l['p_sign_posn'];
        } else {
            $sign = $l['negative_sign'];
            $sign_posn = $l['n_sign_posn'];
        }
        // Currency format:
        $m = number_format(abs($this->value), $l['frac_digits'], $l['mon_decimal_point'], $l['mon_thousands_sep']);
        switch ($sign_posn) {
            case 0: $m = "($m)";
                break;
            case 1: $m = "$sign$m";
                break;
            case 2: $m = "$m$sign";
                break;
            case 3: $m = "$sign$m";
                break;
            case 4: $m = "$m$sign";
                break;
            default: $m = "$m [error sign_posn=$sign_posn&nbsp;!]";
        }
        return $m;
    }

    public function getPlainValue(){
        return $this->getValue();
    }
    public function  __toString() {
        return $this->format();
    }

}

?>

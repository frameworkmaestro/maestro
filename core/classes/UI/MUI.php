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

namespace Maestro\UI;

use Maestro\Manager;

class MUI
{

    public function doPostBack($id)
    {
        return "manager.doPostBack(\"{$id}\");";
    }

    public function doPrintForm()
    {
        return "manager.doPrintForm();";
    }

    public function doPrintFile($id)
    {
        return "manager.doPrintFile(\"{$id}\");";
    }

    public function doShowPDF($id)
    {
        return "manager.doShowPDF(\"{$id}\");";
    }

    public function doPrompt($id)
    {
        return "manager.doPrompt(\"{$id}\");";
    }

    public function showHelp($id)
    {
        return "manager.byId(\"{$id}Help\").show();";
    }

    public function doWindow($url, $target = '')
    {
        return "manager.doWindow(\"{$url}\",\"{$target}\");";
    }

    public function doDialog($id, $url)
    {
        return "manager.doDialog(\"dialog{$name}\",\"{$url}\");";
    }

    public function doGet($url, $target = '')
    {
        return "manager.doGet(\"{$url}\",\"{$target}\");";
    }

    public function doAjaxText($url, $id, $updateElement = '')
    {
        return "manager.doAjaxText(\"{$url}\",\"{$updateElement}\",\"{$id}\");";
    }

    public function doLinkButton($url, $id)
    {
        return "manager.doLinkButton(\"{$url}\",\"{$id}\");";
    }

    public function doRedirect($url)
    {
        return "manager.doRedirect(\"{$url}\");";
    }

    public static function set($id, $attribute, $value = '')
    {
        return "manager.byId(\"{$id}\").set(\"{$attribute}\",\"{$value}\");";
    }

    public static function copyValue($idFrom, $idTo)
    {
        return "manager.copyValue('{$idFrom}','{$idTo}');";
    }

    public static function hide($id)
    {
        return "$('#{$id}').hide();";
    }

    public static function show($id)
    {
        return "$('#{$id}').show();";
    }

    public static function toggle($id)
    {
        return "$('#{$id}').toggle();";
    }

    public static function highlight($id)
    {
        return "$('#{$id}').addClass('bg-warning');";
    }

    public static function replace($id, $html)
    {
        return "$('#{$id}').html('{$html}');";
    }

    public static function setHREF($id, $href)
    {
        $href = Manager::getURL($href);
        return "manager.byId(\"{$id}\").set(\"href\",\"{$href}\");";
    }

}
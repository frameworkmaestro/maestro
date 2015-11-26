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

use Maestro\Utils\MUtil,
    Maestro\Services\MJSON,
    Maestro\UI\MAction;

class Painter extends Maestro\UI\MBasePainter
{

    public function __construct() {
        parent::__construct();
        // Define quais propriedades serão renderizadas como atributos HTML (exceto style)
        $this->attributes = "id,name,readonly,title,value,class,src,alt,enctype,method,onclick,onchange";
    }

    /**
     * Verifica métodos não existentes.
     * @param type $name Nome do método.
     * @param type $args Argumentos da chamada.
     * @throws \Maestro\Services\Exception\EControlException
     */
    public function __call($name, $args) {
        if (!isset($this->$name)) {
            throw new \Maestro\Services\Exception\EControlException("Método {$name} não definido no Painter!");
        }
    }

    public function mbutton($control) {
        $btntype = $control->hasClass("/^btn-/") ? "" : "btn-default";
        $control->addClass('btn ' . $btntype);
        $type = $control->property->type ?: "submit";
        MAction::generate($control);
        return <<<EOT
<button {$this->getAttributes($control)} type={$type}>{$control->property->text}</button>

EOT;
    }

    public function mform($control) {
        return $control->generate();
    }

    public function mpanel($control) {
        $paneltype = $control->hasClass("/^panel-/") ? "" : "panel-default";
        $control->addClass('panel ' . $paneltype);
        $attributes = $this->getAttributes($control);
        if ($control->property->title) {
            $heading = "<div class=\"panel-heading\">{$control->property->title}</div>";
        }
        $children = 'NO CONTENT';
        if ($control->hasItems()) {
            $children = $this->generateToString($control->controls);
        }
        return <<<EOT
<div {$attributes}>
    $heading
    <div class="panel-body">
        {$children}
    </div>
</div>
EOT;

    }

    public function mtextfield($control) {
        $control->setId($control->getId());
        $control->addClass("form-control");
        $attributes = $this->getAttributes($control);
        $type = $control->property->type ?: "text";
        return <<<EOT
<label for="{$control->getId()}">{$control->property->label}</label>
<input $attributes type="{$type}">

EOT;
    }

    public function mselection($control) {
        $control->setId($control->getId());
        $options = '';
        foreach ($control->property->options as $key => $value) {
            $options .= "<option value=\"{$key}\">{$value}</option>" . PHP_EOL;
        }
        $control->style->width = $control->style->width ?: "200px";
        $style = $this->getStyle($control);
        return <<<EOT
<div $style>
    <label for="{$control->getId()}">{$control->property->label}</label>
    <select {$this->getAttributes($control)}  class="combobox form-control">
        $options
    </select>
    <script type="text/javascript">
    $(document).ready(function(){
        $('#{$control->getId()}').combobox();
    });
    </script>
</div>
EOT;
    }

    public function mhiddenfield($control) {
        $control->property->type = "hidden";
        return <<<EOT
<input {$this->getAttributes($control)}>
EOT;
    }

    public function mprompt($control) {
        return $control->generate();
    }

    public function mmenu($control) {
        return $control->generate();
    }

    public function mmenudropdown($control) {
        $children = '';
        if ($control->hasItems()) {
            foreach ($control->controls as $item) {
                $children .= "<li>{$this->generateToString($item)}</li>";
            }
        }
        return <<<EOT
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
<ul class="dropdown-menu" role="menu">
    $children
</ul>
EOT;

    }

    public function mmenuitem($control) {
        MAction::generate($control);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<a href="#" {$attributes}>{$control->property->text}</a>
EOT;

    }

    public function mhcontainer($control) {
        $control->addClass('mHContainer');
        if ($control->hasItems()) {
            foreach ($control->controls as $field) {
                $inner .= "<div class=\"cell\">{$field->generate()}</div>";
            }
            $inner .= '<div class="clear"></div>';
        }

        $attributes = $this->getAttributes($control);
        return <<<EOT
<div {$attributes}>
    {$inner}
</div>
EOT;
    }

    public function mvcontainer($control) {
        $control->addClass('mVContainer');
        $inner = "";
        if ($control->hasItems()) {
            foreach ($control->controls as $field) {
                $inner .= "<div class=\"cell\">{$field->generate()}</div>";
            }
        }
        $attributes = $this->getAttributes($control);
        return <<<EOT
<div {$attributes}>
    {$inner}
</div>
EOT;
    }

    public function mfilefield($control) {
        $inputWidth = $control->style->width ?: "100px";
        $control->addClass("form-control");
        return <<<EOT
<label for="{$control->getId()}">{$control->property->label}</label>
<div style="position:relative;">
    <a class='btn btn-primary' href='javascript:;'>
        Choose File...
        <input type="file" id="{$control->getId()}" style="width:{$inputWidth};" class="inputFile" name="{$control->getId()}_file_source" size="40"  onchange="library.setFileInputText('#{$control->getId()}');">
    </a>
    &nbsp;
    <span class='label label-info' id="{$control->getId()}-label"></span>
    <input type="hidden" id="{$control->getId()}-name" name="{$control->getId()}-name">
</div>
EOT;
    }

    public function mplain($control){
        return $control->text;
    }
}

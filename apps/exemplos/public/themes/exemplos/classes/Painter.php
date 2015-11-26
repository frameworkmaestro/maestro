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

/*
 * Painter usando jQuery EasyUI
 */

use Maestro\Utils\MUtil,
    Maestro\Services\MJSON,
    Maestro\UI\MAction;

class Painter extends Maestro\UI\MBasePainter
{

    public function __construct()
    {
        // Define quais propriedades serão renderizadas como atributos HTML
        $this->attributes = "id,name,style,readonly,title,value,class,src,alt,enctype,method";
        parent::__construct();
    }

    /**
     * Verifica métodos não existentes.
     * @param type $name Nome do método.
     * @param type $args Argumentos da chamada.
     * @throws \Maestro\Services\Exception\EControlException
     */
    public function __call($name, $args)
    {
        if (!isset($this->$name)) {
            throw new \Maestro\Services\Exception\EControlException("Método {$name} não definido no Painter!");
        }
    }

    /*
     * Métodos auxiliares para tratamento de controles EasyUI 
     */

    /**
     * Adiciona a classe CSS referente ao plugin.
     * @param object $control
     */
    public function setPluginClass($control)
    {
        $control->addClass('easyui-' . $control->plugin);
    }

    /**
     * Cria o atributo data-options com base em $control->property->options.
     * Executado a partir de $basePainter->getAttributes().
     * @param object $control
     * @return string
     */
    public function getOptions($control)
    {
        $val = "";
        $value = $control->property->options;
        if (count($value)) {
            $val = substr(MJSON::parse($control->property->options), 1, -1);
            $val = "data-options=\"{$val}\" ";
        }
        return $val;
    }

    /**
     * Cria o comando javascript para definições de opções do plugin.
     * Usado essencialmente para as opções que são funções javascript (ex. eventos).
     * @param object $control
     */
    public function createJS($control)
    {
        $jsOptions = $control->property->jsOptions ? MJSON::parse($control->property->jsOptions) : '{}';
        if ($jsOptions != '') {
            $code = "$('#{$control->id}').{$control->plugin}({$jsOptions});";
            $this->page->onLoad($code);
        }
    }

    /**
     * Gera o codigo javascript referente aos eventos de um controle.
     * Adapta o nome dos eventos ao padrão do EasyUI ('click' => 'onClick').
     * @param array[object] $events
     */
    public function generateEvents($control)
    {
        $events = $control->event;
        if (is_array($events) && count($events)) {
            foreach ($events as $event) {
                foreach ($event as $objEvent) {
                    $preventDefault = $objEvent->preventDefault ? "event.preventDefault();" : "";
                    $function = $objEvent->handler[0] == '!' ? substr($objEvent->handler, 1) : "function(event) { {$objEvent->handler} {$preventDefault} }";
                    if ($control->plugin != '') {
                        $objEvent->event = 'on' . ucfirst($objEvent->event);
                        $code = "$('#{$objEvent->id}').{$control->plugin}({ {$objEvent->event}: {$function} } )";
                    } else {
                        $code = "$('#{$objEvent->id}').on('{$objEvent->event}', {$function} )";
                    }
                    $this->page->onLoad($code);
                }
            }
        }
    }

    /*
     * Output
     */

    public function mlabel($control)
    {
        $attributes = $this->getAttributes($control);
        $inner = $control->text ? : $control->cdata;
        return <<<EOT
<span {$attributes}>
    {$inner}
</span>
EOT;
    }

    public function mfieldlabel($control)
    {
        $label = "";
        if ($control->text) {
            $classes = implode(' ', $control->getClass());
            $class = "class=\"mFormLabel {$classes}\""; 
            $label = "<label for=\"{$control->id}\" {$class}>{$control->text}</label>";
        }
        return $label;
    }

    public function mimage($control)
    {
        $control->property->src = $control->property->src ?: $control->property->source;
        $control->property->alt = $control->property->alt ?: $control->property->label;
        $attributes = $this->getAttributes($control);
        return <<<EOT
<img {$attributes}>
EOT;
    }

    /*
     * Input
     */

    public function minputfield($control)
    {
        $this->setPluginClass($control);
        $control->property->pattern = $control->pattern;
        $control->property->options['width'] = $control->property->style['width'] ? : '150px';
        if ($control->mask) {
            $maskOptions = $control->maskOptions != '' ? ',' . $control->maskOptions : '';
            $onLoad = "$('#{$control->id}').textbox('textbox').mask('{$control->mask}'{$maskOptions});";
            $this->page->onLoad($onLoad);
        }
        //$this->createJS($control);
        // processa os validators e retorna o campo hidden, se necessário
        $hidden = MValidator::process($control);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<input {$attributes}/>{$hidden} 
EOT;
    }

    public function mtextfield($control)
    {
        $control->plugin = 'textbox';
        if ($control->placeholder) {
            $control->property->options['prompt'] = $control->placeholder;
        }
        if ($control->type == "search") {
            $icons = "[{ iconCls: 'icon-search', handler: function(e) { " . $control->action . "}}]";
            $control->property->options['icons'] = (object) $icons;
        }
        $control->property->type = ($control->property->type != 'file') ? $control->property->type : '';
        return $this->minputfield($control);
    }

    public function mhiddenfield($control)
    {
        $attributes = $this->getAttributes($control);
        return <<<EOT
<input type="hidden" {$attributes}/>
EOT;
    }

    public function mpasswordfield($control)
    {
        $control->property->options['type'] = "password";
        return $this->mtextfield($control);
    }

    public function mmultilinefield($control)
    {
        $control->property->rows = $control->rows;
        $control->property->cols = $control->cols;
        $attributes = $this->getAttributes($control);
        return "<textarea {$attributes}>{$control->text}</textarea>";
    }

    public function mcalendarfield($control)
    {
        $control->plugin = 'datebox';
        $control->mask = '00/00/0000';
        $control->placeholder = '__/__/____';
        return $this->minputfield($control);
    }

    public function mtimefield($control)
    {
        $control->plugin = 'timespinner';
        return $this->minputfield($control);
    }

    public function mtimestampfield($control)
    {
        $control->plugin = 'datetimespinner';
        $control->property->value = $control->value;
        return $this->minputfield($control);
    }

    public function memailfield($control)
    {
        $control->plugin = 'validatebox';
        if ($control->form) {
            $control->addValidator((object) ['field' => $control->id, 'type' => 'email']);
        }
        return $this->mtextfield($control);
    }

    public function mnumberfield($control)
    {
        $control->plugin = 'numberbox';
        $control->property->options['decimalSeparator'] = $control->decimalSeparator ? : ',';
        return $this->minputfield($control);
    }

    public function mcurrencyfield($control)
    {
        $control->property->options['precision'] = '2';
        $control->property->options['prefix'] = 'R$ ';
        $control->property->options['groupSeparator'] = '.';
        $control->decimalSeparator = ',';
        return $this->mnumberfield($control);
    }

    public function mnumberspinner($control)
    {
        $control->plugin = 'numberspinner';
        return $this->minputfield($control);
    }

    public function mphonefield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '(00) 0000-0000';
        $control->placeholder = '(00) 0000-0000';
        return $this->mtextfield($control);
    }

    public function mcpffield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '000.000.000-00';
        $control->placeholder = '000.000.000-00';
        $control->maskOptions = "{reverse: true}";
        return $this->mtextfield($control);
    }

    public function mcnpjfield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '00.000.000/0000-00';
        $control->placeholder = '00.000.000/0000-00';
        $control->maskOptions = "{reverse: true}";
        return $this->mtextfield($control);
    }

    public function mnitfield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '000.00000.00-0';
        $control->placeholder = '000.00000.00-0';
        $control->maskOptions = "{reverse: true}";
        return $this->mtextfield($control);
    }

    public function msiapefield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '0000000';
        $control->placeholder = '0000000';
        $control->maskOptions = "{reverse: true}";
        return $this->mtextfield($control);
    }

    public function mcepfield($control)
    {
        $control->plugin = 'numberbox';
        $control->mask = '00000-000';
        $control->placeholder = '00000-000';
        $control->maskOptions = "{reverse: true}";
        return $this->mtextfield($control);
    }

    public function meditor($control)
    {
        $control->jId = '#' . $control->id;
        $html = $this->fetch('meditor', $control);
        $this->page->onSubmit("{$control->id}_submit()", $control->form->id);
        return $html;
    }

    public function minputgrid($control)
    {
        $grid = array();
        for ($i = 1; $i <= $control->numRows; $i++) {
            $grid[$i] = new MHContainer();
            $grid[$i]->addControl(new MDiv(['inner' => $i . ': ', 'width' => '30px']));
            for ($j = 1; $j <= $control->numCols; $j++) {
                $textfield = new MTextField();
                $textfield->id = $control->id . '[' . $i . '][' . $j . ']';
                $textfield->width = '150px';
                $grid[$i]->addControl($textfield);
            }
        }
        return $this->generateToString($grid);
    }

    public function mtext($control)
    {
        return $this->mmultilinefield($control);
    }

    public function mselection($control)
    {
        $control->plugin = 'combobox';
        $data = [];
        if (is_array($control->options)) {
            foreach ($control->options as $value => $label) {
                $option = (object) ['label' => $label, 'value' => $value];
                $data[] = $option;
            }
        }
        $control->property->options['valueField'] = 'value';
        $control->property->options['textField'] = 'label';
        $control->property->options['data'] = (object) MJSON::parse($data);

        $this->setPluginClass($control);
        $attributes = $this->getAttributes($control);
        return "<input {$attributes}>";
    }

    public function mlookup($control)
    {
        /*
        $idHidden = new MHiddenField();
        $idHidden->setId($control->id);

        $textHidden = new MHiddenField();
        $textHidden->setId($control->id . '_text');
        */

        //$id = $control->id;
        //$control->property->id = $id . '_lookup';
        //$control->property->name = $id . '_lookup';
        //$control->property->filters = $control->property->filter;
        $control->property->options['width'] = $control->property->style['width'] ? : '100px';
        //$related = "{$idHidden->id}:{$control->idField},{$textHidden->id}:{$control->textField},";
        //$control->property->related = $related . $control->related;
        //$control->property->filters = $control->filters;
        $control->property->filters .= "," . $control->id;

        $lookup['idField'] = strtoupper($control->idField);
        $lookup['textField'] = strtoupper($control->textField);
        $lookup['url'] = Manager::getAppURL($control->property->action);

        if ($control->hasItems()) {
            foreach ($control->controls as $child) {
                
                if ($child->tag == 'mlookupcolumn') {
                    $columns[] = array(
                        'field' => strtoupper($child->property->field),
                        'hidden' => ($child->property->visible === false),
                        'title' => $child->property->title,
                        'width' => $child->width
                    );
                } /* else {
                    if ($child instanceof mlookupoptions) {
                        $lookup['loadMsg'] = $child->loadMsg ? : "Carregando...";
                        $lookup['minLength'] = $child->minLength;
                        $lookup['panelWidth'] = $child->panelWidth;
                        $lookup['fitColumns'] = $child->fitColumns;
                    }
                }*/
            }
            $lookup['columns'][0] = $columns;
        }
        //$control->property->lookup = urlencode(json_encode($lookup));
        $this->page->addJsCode("$('#{$control->property->id}').data('lookup', '" . MJSON::encode($lookup) . "');\n");
        $this->page->addJsCode("$('#{$control->property->id}').data('related', '" . MJSON::encode($control->property->related) . "');\n");
        $this->page->addJsCode("$('#{$control->property->id}').data('filters', '" . MJSON::encode($control->property->filters) . "');\n");
        
        $this->page->onLoad("mlookup('{$control->property->id}');");
        $attributes = $this->getAttributes($control);
        /*
         {$this->mhiddenfield($idHidden)}
    {$this->mhiddenfield($textHidden)}

         */
        return <<<EOT
    <select {$attributes}></select>
EOT;
    }

    public function mfilefield($control)
    {
        $control->plugin = 'filebox';
        $control->property->options['prompt'] = $control->text;
        $this->setPluginClass($control);
        $control->form->property->enctype = "multipart/form-data";
        return $this->minputfield($control);
    }

    /*
     * Actions
     */

    public function mprompt($control)
    {
        $control->setClass('mprompt');
        $control->property->manager['type'] = $control->type;
        $control->property->manager['msg'] = $control->msg;
        $control->property->manager['action1'] = MAction::parseAction($control->action1);
        $control->property->manager['action2'] = MAction::parseAction($control->action2);
        return $this->mdiv($control);
    }

    public function mtree($control)
    {
        if (count($control->arrayItems)) {
            $key = '3';
            $data = '0,1,2,3,4,5';
            $tree = MUtil::arrayTree($control->arrayItems, $key, $data);
            $control->items = $tree;
        }
        $id = $control->id;
        $tree = $control->items;
        if ($control->checkbox != NULL) {
            $control->property->options['checkbox'] = $control->checkbox;
        }
        $control->plugin = 'tree';
        $internal = $this->mtreeTransverse($tree, $tree['root']);
        $data = "[{$internal}]";
        $control->property->options['data'] = (object) $data;

        $event = str_replace('#action#', 'node.action', $event);

        $onSelect = $control->onSelect;

        if ($onSelect != '') {
            $onSelect = <<<EOT
    function(node) { 
        {$onSelect}
    }
EOT;
        } else {
            $onSelect = <<<EOT
    function(node) { 
        console.log(node);
        if (node.action != '') {
            console.log(node.action);
            manager.doAction(node.action);
        }
    }
EOT;
        }
        $control->property->jsOptions['onSelect'] = (object) $onSelect;
        $this->setPluginClass($control);
        $this->createJS($control);
        $attributes = $this->getAttributes($control);
        $html = "<ul {$attributes}></ul>";
        return $html;
    }

    private function mtreeTransverse($tree, $nodes)
    {
        $text = "";
        if (count($nodes)) {
            foreach ($nodes as $node) {
                $children = $this->mtreeTransverse($tree, $tree[$node[0]]);
                $action = MAction::parseAction($node[2]);
                $state = ($node[4] != '') ? ", state: '{$node[4]}'" : "";
                $check = $node[5] !== null ? ", check: " . ($node[5] ? 'true' : 'false') : "";
                $text .= "{id: '{$node[0]}', text: '{$node[1]}', action: '{$action}', children: [{$children}]{$state}{$check}},";
            }
            $text = substr($text, 0, -1);
        }
        return $text;
    }

    public function mlink($control)
    {
        MAction::generate($control, $control->id);
        $control->property->href = $control->href ? : "#";
        $attributes = $this->getAttributes($control);
        $inner = $control->text ? : $control->cdata;
        return <<<EOT
<a {$attributes}>
    {$inner}
</a>
EOT;
    }

    private function glyphclass($control)
    {
        return $control->glyph ? "glyphicon glyphicon-{$control->glyph}" : "";
    }

    private function glyphicon($control)
    {
        return $control->glyph ? "<div style='padding:3px' class='{$this->glyphclass($control)}' aria-hidden='true'></div>" : "";
    }

    public function mlinkbutton($control)
    {
        $control->property->options['iconCls'] = $control->iconCls ? : $control->icon;
        $control->property->options['plain'] = $control->plain;
        $control->property->options['size'] = $control->size;
        $glyph = $this->glyphicon($control);
        MAction::generate($control, $control->id);
        $control->plugin = 'linkbutton';
        $this->setPluginClass($control);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<a href='#' {$attributes}>
    {$glyph}{$control->property->text}
</a>
EOT;
    }

    public function mbutton($control)
    {
        $control->plugin = 'linkbutton';
        $control->addClass('mFormButton');
        $glyph = $this->glyphicon($control);
        $control->property->options['iconCls'] = $control->iconCls ? : $control->icon;
        $control->property->type = $control->property->type ? : "button";
        MUtil::setIfNull($control->action, 'POST');
        MAction::generate($control);
        $this->setPluginClass($control);
        $attributes = $this->getAttributes($control);
        $text = $glyph . (($control->value != '') ? "{$control->value}" : (($control->text != '') ? "{$control->text}" : "{$control->caption}"));
        return <<<EOT
<button {$type}{$attributes}>
    {$text}
</button>
EOT;
    }

    public function mcontextmenu($control)
    {
        $control->plugin = 'menu';
        $this->setPluginClass($control);
        // captura o evento 'contextmenu' para exibir o menu
        $code = <<<EOT
$('#{$control->context}').bind('contextmenu',function(e){
    e.preventDefault();
    $('#{$control->id}').menu('show', {
        left: e.pageX,
        top: e.pageY 
    }); 
}); 
EOT;
        $this->page->addJsCode($code);
        return $this->mdiv($control);
    }

    public function mmenu($control)
    {
        return $this->mdiv($control);
    }

    public function mmenubutton($control)
    {
        $control->plugin = 'menubutton';
        $control->addClass('mMenuButton');
        MAction::generate($control, $control->id);
        $this->setPluginClass($control);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<a href='#' {$attributes}>
    {$control->property->text}
</a>
EOT;
    }

    public function mmenuitem($control)
    {
        $control->text = $control->text ? : $control->label;
        $control->property->options['iconCls'] = $control->icon ? : $this->glyphclass($control);
        MAction::generate($control);
        $control->addControl($control->text);
        return $this->mdiv($control);
    }

    public function mmenubar($control)
    {
        return $this->mdiv($control);
    }

    public function mmenubaritem($control)
    {
        $control->property->text = $control->label;
        $control->property->options['iconCls'] = $control->icon;
        if ($control->hasItems()) {
            foreach ($control->controls as $c) {
                if ($c->className == 'mmenu') {
                    $control->property->options['menu'] = '#' . $c->id;
                    $menus .= $this->mmenu($c);
                }
            }
        }
        return $this->mmenubutton($control) . $menus;
    }

    public function mmenuseparator($control)
    {
        $control->setClass('menu-sep');
        return $this->mdiv($control);
    }

    public function mtool($control)
    {
        $control->property->title = $control->title;
        MAction::generate($control, $control->id);
        $control->setClass($control->icon);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<a {$attributes}></a>
EOT;
    }

    public function mtoolbutton($control)
    {
        $control->property->title = $control->title;
        return $this->mlinkbutton($control);
    }

    /*
     * Containers
     */

    public function mdiv($control)
    {
        $attributes = $this->getAttributes($control);
        if ($control->hasItems()) {
            $inner = $this->generateToString($control->controls);
        } elseif ($control->property->cdata) {
            $inner = $control->property->cdata;
        } else {
            $inner = $control->inner;
        }
        return <<<EOT
<div {$attributes}>
    {$inner}
</div>
EOT;
    }

    public function mbasegroup($control)
    {
        $control->property->title = $control->property->caption;
        return $this->mdiv($control);
    }

    public function maccordion($control)
    {
        $control->plugin = 'accordion';
        $this->setPluginClass($control);
        return $this->mdiv($control);
    }

    public function mtoolbar($control)
    {
        return $this->mdiv($control);
    }

    public function mform($control)
    {
        return $control->generate();
    }

    public function mcontainer($control)
    {
        return $this->mdiv($control);
    }

    public function mhcontainer($control)
    {
        $control->addClass('mHContainer');
        if ($control->hasItems()) {
            foreach ($control->controls as $field) {
                $label = $field->property->label ? : '';
                $inner .= '<div class="cell">';
                $inner .= $label . $field->generate();
                $inner .= "</div>";
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

    public function mvcontainer($control)
    {
        $control->addClass('mVContainer');
        $inner = "";
        if ($control->hasItems()) {
            foreach ($control->controls as $field) {
                $label = $field->property->label ? : '';
                $inner .= '<div class="cell">';
                if ($control->labelHorizontal) {
                    $inner .= $label . $field->generate();
                } else {
                    $inner .= ($label ? '<div class="cell">' . $label . '</div>' : '') . $field->generate();
                }
                $inner .= "</div>";
            }
        }
        $attributes = $this->getAttributes($control);
        return <<<EOT
<div {$attributes}>
    {$inner}
</div>
EOT;
    }

    public function mcontentpane($control)
    {
        return $this->mdiv($control);
    }

    public function mdatagrid($control)
    {
        return $control->generate();
    }

    public function mdialog($control)
    {
        $control->setId($control->id);
        $control->plugin = 'dialog';
        $control->property->options['closed'] = $control->closed ? : true;
        $control->property->options['modal'] = ($control->modal === false) ? false : true;
        $control->property->options['doSize'] = true;
        if ($control->onClose) {
            $onClose = "function() {" . $control->onClose . "}";
            $control->property->jsOptions['onClose'] = (object) $onClose;
        }
        if ($control->property->file) {
            $dialog = $control->getControlsFromXML($control->property->file);
            $control->addControls($dialog);
        }
        $this->setPluginClass($control);
        $this->createJS($control);
        if ($control->property->state == "open") {
            $this->page->onLoad("$('#{$control->id}').dialog('open');");
        } else {
            $this->page->onLoad("$('#{$control->id}').dialog('close');");
        }
        $div = $this->mdiv($control);
        $this->page->onLoad("$('#{$control->id}').dialog('resize',{width:'auto',height:'auto'});");
        return $div;
    }

    public function mhelp($control)
    {
        return $this->mdialog($control);
    }

    public function mpanel($control)
    {
        $control->addClass('container-fluid');
        $control->property->options['title'] = $control->title;
        if ($control->close != '') {
            $control->property->options['closable'] = true;
            // captura o evento 'onClose' para executar action definida em $control->close
            $action = MAction::parseAction($control->close);
            $onClose = "function(e) {manager.doAction('{$action}');}";
            $control->property->jsOptions['onClose'] = (object) $onClose;
        }
        $inner = "";
        if ($control->menubar) {
            $inner .= $control->menubar->generate();
        }
        if ($control->hasItems()) {
            foreach ($control->controls as $child) {
                $inner .= $child->generate();
            }
        }
        $toolbar = '';
        if ($control->toolbar) {
            $control->toolbar->id = $control->id > '_toolbar';
            $control->property->options['tools'] = "#{$control->toolbar->id}";
            $toolbar = $this->mdiv($control->toolbar);
        }
        \Maestro\Utils\MUtil::setIfNull($this->width, "100%");
        $control->plugin = 'panel';
        $this->setPluginClass($control);
        $this->createJS($control);
        $attributes = $this->getAttributes($control);
        return <<<EOT
<div {$attributes}>
    {$inner}
</div>
{$toolbar}
EOT;
    }

    public function mactionpanel($control)
    {
        $actions = Manager::getActions();
        if ($control->actions) {
            $selection = explode('.', $control->actions);
            do {
                $actions = $actions[current($selection)][5];
            } while (next($selection) !== false);
            foreach ($actions as $action) {
                $link = new MLinkButton();
                $link->iconCls = $action[2];
                $link->plain = true;
                $link->size = 'large';
                $link->property->text = $action[0];
                $link->property->options['iconAlign'] = 'top';
                $link->action = ">" . $action[1];
                MAction::generate($link);
                $control->addControl($link);
            }
        }
        return $this->mpanel($control);
    }

}

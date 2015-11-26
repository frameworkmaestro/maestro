<?php

class MForm extends MControl
{

    public $toValidate = array();
    public $bsValidator = array();
    public $fields;
    public $buttons;
    public $help;

    public function __construct($title = '', $close = '')
    {
        parent::__construct('mform');
        $this->title = $title;
        $this->close = $close;
        $this->fields = NULL;
        $this->buttons = NULL;
        $this->help = NULL;
    }

    protected function _addControl($control)
    {
        parent::_addControl($control);
        $control->setForm($this);
        $className = ($control->className == 'mhtml') ? $control->tag : $control->className;
        if (($className == 'fields') || ($className == 'buttons') || ($className == 'help')) {
            $this->$className = $control;
        } else {
            if ($this->fields == NULL) {
                $this->fields = new MControl();
            }
            $this->fields->addControl($control);
        }
    }

    public function onAfterCreate() {
        if ($this->property->load) {
            $this->setData($this->property->load);
        }
    }
    
    public function onLoad()
    {
        parent::onLoad();
        $this->createFields();
    }

    public function createFields()
    {
        
    }

    public function setFields($fields)
    {
        foreach ($fields as $field) {
            $this->fields->addControl($field);
        }
    }

    public function setButtons($buttons)
    {
        foreach ($buttons as $button) {
            $this->buttons->addControl($button);
        }
    }

    public function generateFields()
    {
        $fields = '';
        $control = $this->fields;
        if ($control->hasItems()) {
            foreach ($control->controls as $field) {
                if ($field->className == 'mhiddenfield') {
                    $fields .= $field->generate();
                } else {
                    $mfieldlabel = new mfieldlabel(['id' => $field->id, 'text' => $field->label]);
                    if ($this->layout == 'horizontal') {
                        $mfieldlabel->setClass($this->labelClass);
                    }
                    $label = $mfieldlabel->generate();
                    $formGroup = $label . $field->generate();
                    // usa a classe form-group do bootstrap
                    $fields .= "<div class=\"form-group\">{$formGroup}</div>";
                }
            }
        }
        return $fields;
    }

    public function generateButtons()
    {
        $inner = "";
        $control = $this->buttons;
        if ($control->hasItems()) {
            foreach ($control->controls as $button) {
                if ($button->action == '') {
                    $button->action = 'POST';
                }
                $inner .= $button->generate();
            }
        }
        return $inner;
    }

    public function generateHelp()
    {
        $inner = "";
        $control = $this->help;
        if ($control->hasItems()) {
            foreach ($control->controls as $help) {
                $inner .= $help->generate();
            }
        }
        return $inner;
    }

    public function generate()
    {
        // panel
        $panel = new MPanel();
        $panel->title = $this->title;
        $panel->width = $this->width;
        $panel->close = $this->close;
        $panel->toolbar = $this->toolbar;
        $panel->class = $this->class;
        $panel->property->options['border'] = isset($this->property->style['border']) ? $this->property->style['border'] : true;
        $panel->generate(); //gera o panel para obter todos os atributos
        // propriedades
        $this->property->action = $this->action ? : Manager::getCurrentURL();
        \Maestro\Utils\MUtil::setIfNull($this->property->method, 'POST');
        \Maestro\Utils\MUtil::setIfNull($this->width, "100%");
        $this->property->role = "form";
        // define o layout com base na classe bootstrap do form
        \Maestro\Utils\MUtil::setIfNull($this->property->layout, "horizontal");
        $this->setClass("form-{$this->layout}");
        // neste tema o mform é constituído de 3 blocos principais: fields, buttons e help
        $fields = $buttons = $help = "";
        if ($this->fields != NULL) {
            $fields = $this->generateFields();
        }
        if ($this->buttons != NULL) {
            $buttons = $this->generateButtons();
        }
        if ($this->help != NULL) {
            $help = $this->generateHelp();
        }
        // menubar
        if ($this->menubar) {
            $menubar = $this->menubar->generate();
        }
        
        // por default, o método de submissão é POST
        \Maestro\Utils\MUtil::setIfNull($this->property->method, "POST");
        
        // por default, o formulário pode ser submetido
        $this->page->onSubmit("true", $this->id);
        
        // se o form tem fields com validators, define onSubmit
        $validators = '';
        if (count($this->toValidate)) {
            $this->page->onSubmit("$('#{$this->id}').form('validate')", $this->id);
            $validators = implode(',', $this->bsValidator);
        }
        
        $submit = <<<EOT
    manager.doPostBack(idForm);
EOT;

      $this->page->submit($submit, $this->id);
        
        // obtem o codigo html via template
        $result = $this->painter->fetch('mform', $this, [
            'panel' => $panel,
            'fields' => $fields,
            'buttons' => $buttons,
            'help' => $help,
            'validators' => $validators,
            'menubar' => $menubar
        ]);
        return $result;
    }

}

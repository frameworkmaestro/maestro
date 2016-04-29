<?php

class MUserBar extends MControl {

    public $cols;
    public $control;

    public function __construct($cols = null) {
        parent::__construct();
        $this->cols = $cols;

        $login = Manager::getLogin();
        if ($login) {
            $this->addInfo(new showDatasource());
            $online = (time() - $login->getTime()) / 60;
            $this->addInfo('[' . _M("Usuário:") . ' ' . $login->getLogin() . ']');
            $logout = new MLink('', _M("Sair"), 'auth/login/logout');
            $this->addInfo('[' . $logout->generate() . '] ');
        } else {
            $controls = $this->getControlsFromXML(dirname(__FILE__) . '/formLogin.xml');
            $control = array_shift($controls); // retorna o primeiro controle definido no arquivo xml
            $div = new MDiv('', $control);
            $this->control = $div;
        }
    }

    public function addInfo($info) {
        $this->cols[] = $info;
    }

    public function clear() {
        unset($this->cols);
    }

    public function generate() {
        if (count($this->cols)) {
            $ul = new MUnOrderedList();
            $ul->addOptions($this->cols);
            $div = new MDiv('', $ul, 'mUserBar');
        } else {
            $div = $this->control;
        }
        return $div->generate();
    }

}

?>

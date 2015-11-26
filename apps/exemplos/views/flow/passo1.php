<?php

class passo1 extends MFormAction {

    function __construct() {
        parent::__construct('Passo 1', '>examples/main/main');
    }

    public function createFields() {
        $this->setFieldsFromXML('passo1.xml');
    }

}


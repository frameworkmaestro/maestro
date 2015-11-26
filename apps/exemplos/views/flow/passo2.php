<?php

class passo2 extends MFormAction {

    function __construct() {
        parent::__construct('Passo 2', '>examples/main/main');
    }

    public function createFields() {
        $this->setFieldsFromXML('passo2.xml');
    }

}


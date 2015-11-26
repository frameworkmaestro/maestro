<?php

class passo3 extends MFormAction {

    function __construct() {
        parent::__construct('Passo 3', '>examples/main/main');
    }

    public function createFields() {
        $this->setFieldsFromXML('passo3.xml');
    }

}


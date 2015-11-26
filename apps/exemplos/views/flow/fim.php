<?php

class fim extends MFormAction {

    function __construct() {
        parent::__construct('Fim', '>examples/main/main');
    }


    public function createFields() {
        $this->setFieldsFromXML('fim.xml');

    }

}

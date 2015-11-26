<?php

class formFind extends MForm {

    function __construct(){
        parent::__construct('Pesquisar Pessoas', 'exemplos/main');
    }

    function createFields() {
        $this->setFieldsFromXML('formFind.xml');
    }
}

?>

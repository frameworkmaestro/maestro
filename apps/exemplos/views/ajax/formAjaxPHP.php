<?php

class formAjaxPHP extends MForm {

    function __construct() {
        parent::__construct('Ajax via PHP',">controls/main");
    }

    function createFields() {
        // constroi um array com as letras do alfabeto
        $i = 65;
        while (($char = chr($i++)) <= 'Z') {
            $this->data->letras[$char] = $char;
        }
        // define os campos a partir do arquivo XML
        $this->setFieldsFromXML('formAjaxPHP.xml');
        // ajaxText: a action "controls/ajaxPessoa" retorna um código HTML que será colocado dentro do controle "pessoas"
        $this->letra->ajaxText('onChange','ajax/ajaxPessoa', 'pessoas');
        // ajaxEvent: a action "controls/ajaxPessoaJSON" retorna dados no formato JSON
        // que será usado para atualizar o controle MSelection "pessoasJson"
        $this->letraJson->ajaxEvent('onSelectionChange','ajax/ajaxPessoaJSON');
    }
}

?>

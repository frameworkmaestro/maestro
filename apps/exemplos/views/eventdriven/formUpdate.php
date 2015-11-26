<?php

Manager::import('exemplos\models\*');

class formUpdate extends MForm {

    function __construct(){
        parent::__construct('Atualizar Dados Pessoais', 'exemplos/main');
    }

    function createFields() {
        $this->setFieldsFromXML('formUpdate.xml');
    }
    
    function btnPost() {
        try {
            $model = new Pessoa($this->data->id);
            $model->setData($this->data);
            $model->save();
            $this->addInfo('Ok!');
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
    }    
}

?>

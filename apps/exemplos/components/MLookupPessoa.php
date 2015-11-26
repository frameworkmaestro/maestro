<?php

class MLookupPessoa extends MLookupComponente {

     public function onAfterCreate(){
        parent::onAfterCreate();
        $this->setAction('exemplos/pessoa/lookup');
        $this->setSize(35);
        $this->setRelated($this->idHidden  . ':idPessoa,' . $this->getId() . ':nome');
    }

    public function init($name = '', $value = NULL, $label = '', $related = '') {
        $this->object = $value ? : NULL;
        parent::init($name ? : 'pessoa', $this->getObjectId(), $label ? : 'Pessoa', 35, 'exemplos/pessoa/lookup', 'idPessoa,nome', $name ? : 'pessoa');
    }

    public function getFieldValue(){
        return $this->object->nome;
    }

    public function getObjectId(){
        return $this->object ? $this->object->idPessoa : '';
    }
}

?>
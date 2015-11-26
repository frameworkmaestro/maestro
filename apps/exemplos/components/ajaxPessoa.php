<?php

class ajaxPessoa extends MSimpleForm {

     public function onCreate(){
        parent::onCreate();
        $textField = new MTextField('ajaxPessoa[idPessoa]','','IdPessoa');
        $this->addField(new MHContainer('ajaxPessoa', array(
            $textField,
            new MContentPane('panePessoa')
            )
          )      
        );
        $textField->ajaxText('onBlur','ajaxPessoa/getNome', 'panePessoa');
    }
    
    public function getNome() {
        return new MLabel('Nome da Pessoa');
    }

}

?>
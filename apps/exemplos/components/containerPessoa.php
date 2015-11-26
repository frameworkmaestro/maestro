<?php

class containerPessoa extends MVContainer {

    public function setMessage($value){
        $this->property->message = $value;
    }
    
    public function getMessage(){
        return $this->property->message;
    }
    
    public function onAfterCreate(){
        mtracestack();
        $this->addControl(new MLabel($this->getMessage()));
        $this->addControl(new MTextField('componentPessoa[idPessoa]','','IdPessoa'));
        $this->addControl(new MTextField('componentPessoa[nome]','','Nome'));
        $this->addControl(new MCalendarField('component[dataNascimento]','','Nascimento'));
        $this->backgroundColor = '#EEE';
    }
    
}

?>
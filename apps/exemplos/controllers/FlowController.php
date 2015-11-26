<?php

class FlowController extends MController {

   
    public function main() {
        $this->render();
    }

    public function passo1(){
        $this->render();
    }
    
    public function passo2(){
        $table = new exemplos\models\Table($this->data->id);
        $this->data->object = $table->getData();
        $filter->nome = $this->data->nome;
        $this->data->query = $table->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function passo3(){
        $this->render();
    }

    public function fim(){
        $this->render();
    }
    

}
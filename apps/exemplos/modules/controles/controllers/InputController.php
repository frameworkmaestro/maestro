<?php

Manager::import('exemplos/models/*');

class InputController extends MController {

    public function main() {
        $this->render();
    }

    public function input() {
        $this->render();
    }

    public function formTextField() {
        $this->data->email = 'a@teste.com';
        $this->data->nome = "Teste Exemplo";
        $this->data->currency = Manager::currency(1234.56);
        $this->data->dataNascimento = Manager::date(Manager::getSysDate());
        $this->data->timestamp = Manager::timestamp(Manager::getSysTime());
        $this->render();
    }

    public function formInputGrid() {
        $this->render();
    }
    
    public function formSelection() {
        // selection from query
        $pessoa = new Pessoa();
        $this->data->object = $pessoa->getData();
        $filter = new stdClass();
        $filter->nome = $this->data->nome;
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 1);
        // selection from simple array
        $this->data->simple = array('A' => 'Opção A', 'B' => 'Opção B', 'C' => 'Opção C', 'D' => 'Opção D', 'E' => 'Opção E');
        // selection from simple array
        $this->data->group = array(
            'A' => array('A1' => 'Opção A1', 'A2' => 'Opção A2', 'A3' => 'Opção A3'),
            'B' => array('B1' => 'Opção B1', 'B2' => 'Opção B2', 'B3' => 'Opção B3'),
            'C' => array('C1' => 'Opção C1', 'C2' => 'Opção C2', 'C3' => 'Opção C3')
        );
        $this->render();
    }

    public function formLookup() {
        $this->render();
    }


}
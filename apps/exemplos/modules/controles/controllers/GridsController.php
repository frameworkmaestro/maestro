<?php

Manager::import('crud\models\*');

class GridsController extends MController {

    public function main() {
        $this->render();
    }

    public function formArrayGrid() {
        $this->data->textoAtivo = array('0'=>'Não','1'=>'Sim');
        $this->data->url = Manager::getAppURL('exemplos','controles/grids/formArrayGridData');
        $this->render();
    }

    public function formArrayGridData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // A action deve retornar um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new Pessoa();
        $criteria = $pessoa->listByFilter($this->data->filter);
        $total = $criteria->asQuery()->count();
        $criteria->range($this->data->page, $this->data->rows);
        $result = $criteria->asQuery()->getResult();
        $data = $pessoa->simulaCalculo($result);
        $this->renderJSON($pessoa->gridDataAsJSON($data, false, $total));
    }
    
    public function formQueryGrid() {
        $pessoa = new Pessoa($this->data->id);
        $this->data->object = $pessoa->getData();
        $filter->nome = $this->data->nome;
        // Para paginação, deve ser informada como query a URL da action que retorna os dados
        $this->data->query = Manager::getAppURL('exemplos','controles/grids/formQueryGridData');
        // Para não haver paginação, pode ser informada a query que retorna os dados
        //$this->data->query = $pessoa->listByFilter($filter)->asQuery();
        //
        // Os dados de retorno do grid estão em um objeto em $this->data->{idGrid}_data
        $this->data->selecionados = implode(':', $this->data->gridFind_data->checked);
        mdump($this->data);
        $this->render();
    }
    
    public function formQueryGridData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // O método $this->gridDataAsJSON recebe um criteria e retorna um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new Pessoa();
        $criteria = $pessoa->listByFilter($this->data->filter);
        $this->renderJSON($pessoa->gridDataAsJSON($criteria));
    }
    
    public function gridSave(){
        $gridData = json_decode($this->data->gridFind_data);
        $this->renderPrompt('information', 'OK Save - Id = ' . $gridData->idValue);
    }

    public function gridDelete(){
        $gridData = json_decode($this->data->gridFind_data);
        $this->renderPrompt('information', 'OK Delete - Id = ' . $gridData->idValue);
    }

    public function formFullGrid() {
        // Para paginação, deve ser informada a URL da action que retorna os dados
        $this->data->url = Manager::getAppURL('exemplos','controles/grids/formFullGridData');
        $this->render();
    }

    public function formFullGridData() {
        // Devem estar definidos em $this->data:
        //  - $this->data->page: numero da página da consulta
        //  - $this->data->rows: quantidade de linhas para a consulta
        // O método $this->gridDataAsJSON recebe um criteria e retorna um objeto JSON com
        //  - total: número total de linhas obtidas na consulta
        //  - rows: array com os dados de cada linha
        $pessoa = new Pessoa();
        $criteria = $pessoa->listFuncionario();
        $this->renderJSON($pessoa->gridDataAsJSON($criteria));
    }
    
}
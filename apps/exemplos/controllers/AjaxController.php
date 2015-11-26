<?php

use exemplos\models as models;
Manager::import('exemplos\models\*');

class AjaxController extends MController {

    public function main() {
        $this->render();
    }

    public function formAjaxXML() {
        // constroi um array com as letras do alfabeto
        $i = 65;
        $this->data->location = Manager::getStaticURL('exemplos','images/logo.png');
        while (($char = chr($i++)) <= 'Z') {
            $this->data->letras[$char] = $char;
        }
        $this->render();
    }

    public function ajaxPessoa() {
        $pessoa = new models\Pessoa();
        $filter->nome = $this->data->letra;
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function ajaxPessoaJSON() {
        $pessoa = new models\Pessoa();
        $filter->nome = $this->data->letraJson;
        $this->data->ajaxReturn = $pessoa->listByFilter($filter)->asQuery()->storeResult(0, 1);
        $this->data->ajaxReturn->control = "pessoasJson";
        $this->renderJSON();
    }

    public function ajaxImplicit() {
        $pessoa = new models\Pessoa();
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function ajaxMultiplo() {
        $pessoa = new models\Pessoa();
        $this->data->multiplo1 = Manager::getURL('ajax/ajaxMultiplo1');
        $this->data->multiplo2 = Manager::getURL('ajax/ajaxMultiplo2');
        $this->data->multiplo3 = Manager::getURL('ajax/ajaxMultiplo3');
        $this->data->multiplo4 = Manager::getURL('ajax/ajaxMultiplo4');
        $this->render();
    }

    public function ajaxMultiplo1() {
        $pessoa = new models\Pessoa();
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function ajaxMultiplo2() {
        $pessoa = new models\Pessoa();
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function ajaxMultiplo3() {
        $pessoa = new models\Pessoa();
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function ajaxMultiplo4() {
        $pessoa = new models\Pessoa();
        $this->data->options = $pessoa->listAll()->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function formGridAjax() {
        $setor = new models\Setor();
        $this->data->query = $setor->listAll()->asQuery();
        $this->render();
    }

    public function ajaxGrid() {
        $object = new models\Usuario();
        $this->data->query = $object->listBySetor($this->data->id)->asQuery();
        $this->render();
    }

    public function formAjaxControls(){
        $this->data->pessoas = Pessoa::create()->listAll()->asQuery()->chunkResult();
        $this->data->toolButton = "What.";
        $this->data->location = Manager::getStaticURL("exemplos","images/logo.png");

        $this->render();
    }

    public function ajaxTextField(){
        $this->data->labelField = $this->data->textField;
        $this->render();
    }

     public function ajaxSelection(){
        $this->data->labelField = Pessoa::create($this->data->selectionField)->getNome();
        $this->render();
    }

    public function ajaxToolButton(){
        $this->data->toolButton = "Good job";
        $this->render();
    }

    public function ajaxRadioButton(){
        $this->data->labelField = $this->data->rdgroup1;
        $this->render();
    }

    public function ajaxCheckBox(){
        $this->data->labelField = $this->data->chkgrp1 . ' ' . $this->data->chkgrp2 . ' ' . $this->data->chkgrp3 . ' ' . $this->data->chkgrp4;
        $this->render();
    }

    public function ajaxDiv(){
        $this->data->labelField = "Box";
        $this->render();
    }

    public function ajaxImage(){
        $this->data->imageLabel = "Ajax Image";
        $this->render();
    }

    public function ajaxButton(){
        $this->data->timesPressed = $this->data->timesPressed +1;
        $this->render();
    }

    public function ajaxLink(){
        $this->data->linkPressed = $this->data->linkPressed +1;
        $this->data->linkLabel = "Link ".$this->data->linkPressed ;
        $this->render();
    }
    
    public function ajaxLoading() {
        sleep(5);
        $this->data->randomNumber1 = rand();
        $this->data->randomNumber2 = rand();
        $this->data->randomNumber3 = rand();
        $this->render();
    }    
}
?>
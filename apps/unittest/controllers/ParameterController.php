<?php
/**
 * $_comment
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage $_package
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

Manager::import("unittest\models\*");

class ParameterController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Parameter= new Parameter($this->data->id);
        $filter->idParameter = $this->data->idParameter;
        $this->data->query = $Parameter->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@unittest/Parameter/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Parameter = Parameter::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Parameter= new Parameter($this->data->id);
        $this->data->Parameter = $Parameter->getData();
        $this->data->Parameter->idMethodDesc = $Parameter->getMethod()->getDescription();
	
        $this->data->action = '@unittest/Parameter/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Parameter = new Parameter($this->data->id);
        $ok = '>unittest/Parameter/delete/' . $Parameter->getId();
        $cancelar = '>unittest/Parameter/formObject/' . $Parameter->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Parameter [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Parameter();
        $filter->idParameter = $this->data->idParameter;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Parameter = new Parameter($this->data->Parameter);
            $Parameter->save();
            $go = '>unittest/Parameter/formObject/' . $Parameter->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Parameter = new Parameter($this->data->id);
            $Parameter->delete();
            $go = '>unittest/Parameter/formFind';
            $this->renderPrompt('information',"Parameter [{$this->data->idParameter}] removido.", $go);
    }

}
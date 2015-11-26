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

class ModelController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Model= new Model($this->data->id);
        $filter->idModel = $this->data->idModel;
        $this->data->query = $Model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@unittest/Model/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Model = Model::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Model= new Model($this->data->id);
        $this->data->Model = $Model->getData();
        $this->data->Model->idAppDesc = $Model->getApp()->getDescription();
	
        $this->data->action = '@unittest/Model/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Model = new Model($this->data->id);
        $ok = '>unittest/Model/delete/' . $Model->getId();
        $cancelar = '>unittest/Model/formObject/' . $Model->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Model [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Model();
        $filter->idModel = $this->data->idModel;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Model = new Model($this->data->Model);
            $Model->save();
            $go = '>unittest/Model/formObject/' . $Model->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Model = new Model($this->data->id);
            $Model->delete();
            $go = '>unittest/Model/formFind';
            $this->renderPrompt('information',"Model [{$this->data->idModel}] removido.", $go);
    }

}
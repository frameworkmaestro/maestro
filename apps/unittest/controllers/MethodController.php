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

class MethodController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Method= new Method($this->data->id);
        $filter->idMethod = $this->data->idMethod;
        $this->data->query = $Method->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@unittest/Method/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Method = Method::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Method= new Method($this->data->id);
        $this->data->Method = $Method->getData();
        $this->data->Method->idModelDesc = $Method->getModel()->getDescription();
	
        $this->data->action = '@unittest/Method/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Method = new Method($this->data->id);
        $ok = '>unittest/Method/delete/' . $Method->getId();
        $cancelar = '>unittest/Method/formObject/' . $Method->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Method [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Method();
        $filter->idMethod = $this->data->idMethod;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Method = new Method($this->data->Method);
            $Method->save();
            $go = '>unittest/Method/formObject/' . $Method->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Method = new Method($this->data->id);
            $Method->delete();
            $go = '>unittest/Method/formFind';
            $this->renderPrompt('information',"Method [{$this->data->idMethod}] removido.", $go);
    }

}
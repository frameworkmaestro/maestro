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

Manager::import("$_module\models\*");

class $_modelCController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $$_model= new $_modelC($this->data->id);
        $filter->$_lookup = $this->data->$_lookup;
        $this->data->query = $$_model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@$_module/$_model/save';
        $this->render();
    }

    public function formObject() {
        $this->data->$_model = $_modelC::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $$_model= new $_modelC($this->data->id);
        $this->data->$_model = $$_model->getData();
        $_lookupForeginInstance
        $this->data->action = '@$_module/$_model/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $$_model = new $_modelC($this->data->id);
        $ok = '>$_module/$_model/delete/' . $$_model->getId();
        $cancelar = '>$_module/$_model/formObject/' . $$_model->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do $_modelC [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new $_modelC();
        $filter->$_lookup = $this->data->$_lookup;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $$_model = new $_modelC($this->data->$_model);
            $$_model->save();
            $go = '>$_module/$_model/formObject/' . $$_model->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $$_model = new $_modelC($this->data->id);
            $$_model->delete();
            $go = '>$_module/$_model/formFind';
            $this->renderPrompt('information',"$_modelC [{$this->data->$_lookup}] removido.", $go);
    }

}
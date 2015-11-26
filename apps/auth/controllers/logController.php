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

Manager::import("auth\models\*");

class LogController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new models\Log($this->data->id);
        $this->data->object = $model->getData();
        $filter->idLog = $this->data->idLog;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@log/save';
        $this->render();
    }

    public function formObject() {
        $model = new models\Log($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new models\Log($this->data->id);
        $this->data->object = $model->getData();
        $this->data->action = '@log/save/' . $this->data->object->id;
        $this->render();
    }

    public function formDelete() {
        $model = new models\Log($this->data->id);
        $ok = '>auth/log/delete/' . $this->data->id;
        $cancelar = '>auth/log/formObject/' . $this->data->id;
        $this->renderPrompt('confirmation', "Confirma remoção do Log [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new models\Log();
        $filter->idLog = $this->data->idLog;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new models\Log($this->data->id);
            $model->setData($this->data);
            $model->save();
            $go = '>auth/log/formObject/' . $model->getId();
            $this->renderPrompt('information','OK',$go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new models\Log($this->data->id);
            $model->delete();
            $go = '>auth/log/formFind';
            $this->renderPrompt('information',"Log [{$this->data->idLog}] removido.", $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

}
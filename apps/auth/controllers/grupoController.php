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

use auth\models as models;
Manager::import('auth\models\*');

class GrupoController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new models\Grupo($this->data->id);
        $this->data->object = $model->getData();
        $filter->grupo = $this->data->grupo;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@grupo/save';
        $this->render();
    }

    public function formObject() {
        $model = new models\Grupo($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new models\Grupo($this->data->id);
        $this->data->object = $model->getData();
        $this->data->action = '@grupo/save/' . $this->data->object->id;
        $this->render();
    }

    public function formDelete() {
        $model = new models\Grupo($this->data->id);
        $ok = '>auth/grupo/delete/' . $this->data->id;
        $cancelar = '>auth/grupo/formObject/' . $this->data->id;
        $this->renderPrompt('confirmation', "Confirma remoção do Grupo [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new models\Grupo();
        $filter->grupo = $this->data->grupo;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new models\Grupo($this->data->id);
            $model->setData($this->data);
            $model->save();
            $go = '>auth/grupo/formObject/' . $model->getId();
            $this->renderPrompt('information','OK',$go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new models\Grupo($this->data->id);
            $model->delete();
            $go = '>auth/grupo/formFind';
            $this->renderPrompt('information',"Grupo [{$this->data->grupo}] removido.", $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formUsuario(){
        $model = new models\Grupo($this->data->id);
        $this->data->object = $model->getData();        
        $this->data->usuarios = Usuario::create()->listAll('','','login')->asQuery()->chunkResult();        
        $this->render();
    }

}
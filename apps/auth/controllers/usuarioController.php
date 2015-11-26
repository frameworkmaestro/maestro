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

class UsuarioController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new Usuario($this->data->id);
        $this->data->object = $model->getData();
        $filter->login = $this->data->login;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@usuario/save';
        $this->render();
    }

    public function formObject() {
        $model = new Usuario($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new Usuario($this->data->id);
        $this->data->object = $model->getData();
        $this->data->action = '@usuario/save/' . $this->data->object->id;
        $this->render();
    }

    public function formDelete() {
        $model = new Usuario($this->data->id);
        $ok = '>auth/usuario/delete/' . $this->data->id;
        $cancelar = '>auth/usuario/formObject/' . $this->data->id;
        $this->renderPrompt('confirmation', "Confirma remoção do Usuario [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Usuario();
        $filter->login = $this->data->login;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new Usuario($this->data->id);
            $model->setData($this->data);
            $model->save();
            $go = '>auth/usuario/formObject/' . $model->getId();
            $this->renderPrompt('information','OK',$go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new Usuario($this->data->id);
            $model->delete();
            $go = '>auth/usuario/formFind';
            $this->renderPrompt('information',"Usuario [{$this->data->login}] removido.", $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formGrupo(){
        $model = new Usuario($this->data->id);
        $this->data->object = $model->getData();        
        $this->data->grupos = Grupo::create()->listAll()->asQuery()->chunkResult();
        $this->render();
    }

}
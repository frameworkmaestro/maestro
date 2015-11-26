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

class TransacaoController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new Transacao($this->data->id);
        $this->data->object = $model->getData();
        $filter->transacao = $this->data->transacao;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@auth/transacao/save';
        $this->render();
    }

    public function formObject() {
        $model = new Transacao($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new Transacao($this->data->id);
        $this->data->object = $model->getData();
        $this->data->action = '@auth/transacao/save/' . $this->data->object->id;
        $this->render();
    }

    public function formDelete() {
        $model = new Transacao($this->data->id);
        $ok = '>auth/transacao/delete/' . $this->data->id;
        $cancelar = '>auth/transacao/formObject/' . $this->data->id;
        $this->renderPrompt('confirmation', "Confirma remoção do Transacao [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Transacao();
        $filter->transacao = $this->data->transacao;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new Transacao($this->data->id);
            $model->setData($this->data);
            $model->save();
            $go = '>auth/transacao/formObject/' . $model->getId();
            $this->renderPrompt('information','OK',$go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new Transacao($this->data->id);
            $model->delete();
            $go = '>auth/transacao/formFind';
            $this->renderPrompt('information',"Transacao [{$this->data->transacao}] removido.", $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error',$e->getMessage());
        }
    }
    
    public function formAcesso(){
        $model = new Transacao($this->data->id);
        $this->data->object = $model->getData();
        $this->data->grupos = Grupo::create()->listAll()->asQuery()->getResult();        
        $this->data->direitos = Manager::getPerms()->getPerms();
        $this->render();
    }

}
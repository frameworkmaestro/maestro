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

class AccessController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Access= new Access($this->data->id);
        $filter->idAccess = $this->data->idAccess;
        $this->data->query = $Access->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@auth/Access/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Access = Access::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Access= new Access($this->data->id);
        $this->data->Access = $Access->getData();
        
        $this->data->action = '@auth/Access/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Access = new Access($this->data->id);
        $ok = '>auth/Access/delete/' . $Access->getId();
        $cancelar = '>auth/Access/formObject/' . $Access->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Access [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Access();
        $filter->idAccess = $this->data->idAccess;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Access = new Access($this->data->Access);
            $Access->save();
            $go = '>auth/Access/formObject/' . $Access->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Access = new Access($this->data->id);
            $Access->delete();
            $go = '>auth/Access/formFind';
            $this->renderPrompt('information',"Access [{$this->data->idAccess}] removido.", $go);
    }

}
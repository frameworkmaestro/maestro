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

Manager::import("fnbr20\models\*");

class EntityController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Entity= new Entity($this->data->id);
        $filter->idEntity = $this->data->idEntity;
        $this->data->query = $Entity->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Entity/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Entity = Entity::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Entity= new Entity($this->data->id);
        $this->data->Entity = $Entity->getData();
        
        $this->data->action = '@fnbr20/Entity/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Entity = new Entity($this->data->id);
        $ok = '>fnbr20/Entity/delete/' . $Entity->getId();
        $cancelar = '>fnbr20/Entity/formObject/' . $Entity->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Entity [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Entity();
        $filter->idEntity = $this->data->idEntity;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Entity = new Entity($this->data->Entity);
            $Entity->save();
            $go = '>fnbr20/Entity/formObject/' . $Entity->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Entity = new Entity($this->data->id);
            $Entity->delete();
            $go = '>fnbr20/Entity/formFind';
            $this->renderPrompt('information',"Entity [{$this->data->idEntity}] removido.", $go);
    }

}
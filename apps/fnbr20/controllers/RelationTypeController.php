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

class RelationTypeController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $RelationType= new RelationType($this->data->id);
        $filter->idRelationType = $this->data->idRelationType;
        $this->data->query = $RelationType->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/RelationType/save';
        $this->render();
    }

    public function formObject() {
        $this->data->RelationType = RelationType::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $RelationType= new RelationType($this->data->id);
        $this->data->RelationType = $RelationType->getData();
        
        $this->data->action = '@fnbr20/RelationType/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $RelationType = new RelationType($this->data->id);
        $ok = '>fnbr20/RelationType/delete/' . $RelationType->getId();
        $cancelar = '>fnbr20/RelationType/formObject/' . $RelationType->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do RelationType [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new RelationType();
        $filter->idRelationType = $this->data->idRelationType;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $RelationType = new RelationType($this->data->RelationType);
            $RelationType->save();
            $go = '>fnbr20/RelationType/formObject/' . $RelationType->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $RelationType = new RelationType($this->data->id);
            $RelationType->delete();
            $go = '>fnbr20/RelationType/formFind';
            $this->renderPrompt('information',"RelationType [{$this->data->idRelationType}] removido.", $go);
    }

}
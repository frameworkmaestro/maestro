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

class EntityRelationController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $EntityRelation= new EntityRelation($this->data->id);
        $filter->idEntityRelation = $this->data->idEntityRelation;
        $this->data->query = $EntityRelation->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/EntityRelation/save';
        $this->render();
    }

    public function formObject() {
        $this->data->EntityRelation = EntityRelation::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $EntityRelation= new EntityRelation($this->data->id);
        $this->data->EntityRelation = $EntityRelation->getData();
        
        $this->data->action = '@fnbr20/EntityRelation/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $EntityRelation = new EntityRelation($this->data->id);
        $ok = '>fnbr20/EntityRelation/delete/' . $EntityRelation->getId();
        $cancelar = '>fnbr20/EntityRelation/formObject/' . $EntityRelation->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do EntityRelation [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new EntityRelation();
        $filter->idEntityRelation = $this->data->idEntityRelation;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $EntityRelation = new EntityRelation($this->data->EntityRelation);
            $EntityRelation->save();
            $go = '>fnbr20/EntityRelation/formObject/' . $EntityRelation->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $EntityRelation = new EntityRelation($this->data->id);
            $EntityRelation->delete();
            $go = '>fnbr20/EntityRelation/formFind';
            $this->renderPrompt('information',"EntityRelation [{$this->data->idEntityRelation}] removido.", $go);
    }

}
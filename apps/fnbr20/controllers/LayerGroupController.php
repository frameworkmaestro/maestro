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

class LayerGroupController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $LayerGroup= new LayerGroup($this->data->id);
        $filter->idLayerGroup = $this->data->idLayerGroup;
        $this->data->query = $LayerGroup->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/LayerGroup/save';
        $this->render();
    }

    public function formObject() {
        $this->data->LayerGroup = LayerGroup::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $LayerGroup= new LayerGroup($this->data->id);
        $this->data->LayerGroup = $LayerGroup->getData();
        
        $this->data->action = '@fnbr20/LayerGroup/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $LayerGroup = new LayerGroup($this->data->id);
        $ok = '>fnbr20/LayerGroup/delete/' . $LayerGroup->getId();
        $cancelar = '>fnbr20/LayerGroup/formObject/' . $LayerGroup->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do LayerGroup [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new LayerGroup();
        $filter->idLayerGroup = $this->data->idLayerGroup;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $LayerGroup = new LayerGroup($this->data->LayerGroup);
            $LayerGroup->save();
            $go = '>fnbr20/LayerGroup/formObject/' . $LayerGroup->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $LayerGroup = new LayerGroup($this->data->id);
            $LayerGroup->delete();
            $go = '>fnbr20/LayerGroup/formFind';
            $this->renderPrompt('information',"LayerGroup [{$this->data->idLayerGroup}] removido.", $go);
    }

}
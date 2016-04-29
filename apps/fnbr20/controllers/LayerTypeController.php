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

class LayerTypeController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $LayerType= new LayerType($this->data->id);
        $filter->idLayerType = $this->data->idLayerType;
        $this->data->query = $LayerType->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/LayerType/save';
        $this->render();
    }

    public function formObject() {
        $this->data->LayerType = LayerType::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $LayerType= new LayerType($this->data->id);
        $this->data->LayerType = $LayerType->getData();
        
        $this->data->action = '@fnbr20/LayerType/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $LayerType = new LayerType($this->data->id);
        $ok = '>fnbr20/LayerType/delete/' . $LayerType->getId();
        $cancelar = '>fnbr20/LayerType/formObject/' . $LayerType->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do LayerType [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new LayerType();
        $filter->idLayerType = $this->data->idLayerType;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $LayerType = new LayerType($this->data->LayerType);
            $LayerType->save();
            $go = '>fnbr20/LayerType/formObject/' . $LayerType->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $LayerType = new LayerType($this->data->id);
            $LayerType->delete();
            $go = '>fnbr20/LayerType/formFind';
            $this->renderPrompt('information',"LayerType [{$this->data->idLayerType}] removido.", $go);
    }

}
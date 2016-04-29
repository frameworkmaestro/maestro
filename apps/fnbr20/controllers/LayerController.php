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

class LayerController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Layer= new Layer($this->data->id);
        $filter->idLayer = $this->data->idLayer;
        $this->data->query = $Layer->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Layer/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Layer = Layer::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Layer= new Layer($this->data->id);
        $this->data->Layer = $Layer->getData();
        
        $this->data->action = '@fnbr20/Layer/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Layer = new Layer($this->data->id);
        $ok = '>fnbr20/Layer/delete/' . $Layer->getId();
        $cancelar = '>fnbr20/Layer/formObject/' . $Layer->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Layer [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Layer();
        $filter->idLayer = $this->data->idLayer;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Layer = new Layer($this->data->Layer);
            $Layer->save();
            $go = '>fnbr20/Layer/formObject/' . $Layer->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Layer = new Layer($this->data->id);
            $Layer->delete();
            $go = '>fnbr20/Layer/formFind';
            $this->renderPrompt('information',"Layer [{$this->data->idLayer}] removido.", $go);
    }

}
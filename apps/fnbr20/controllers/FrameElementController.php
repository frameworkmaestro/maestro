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

class FrameElementController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData(){
        $model = new FrameElement();
        $criteria = $model->listForLookup($this->data->id);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formFind() {
        $FrameElement= new FrameElement($this->data->id);
        $filter->idFrameElement = $this->data->idFrameElement;
        $this->data->query = $FrameElement->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/FrameElement/save';
        $this->render();
    }

    public function formObject() {
        $this->data->FrameElement = FrameElement::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $FrameElement= new FrameElement($this->data->id);
        $this->data->FrameElement = $FrameElement->getData();
        
        $this->data->action = '@fnbr20/FrameElement/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $FrameElement = new FrameElement($this->data->id);
        $ok = '>fnbr20/FrameElement/delete/' . $FrameElement->getId();
        $cancelar = '>fnbr20/FrameElement/formObject/' . $FrameElement->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do FrameElement [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new FrameElement();
        $filter->idFrameElement = $this->data->idFrameElement;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $FrameElement = new FrameElement($this->data->FrameElement);
            $FrameElement->save();
            $go = '>fnbr20/FrameElement/formObject/' . $FrameElement->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $FrameElement = new FrameElement($this->data->id);
            $FrameElement->delete();
            $go = '>fnbr20/FrameElement/formFind';
            $this->renderPrompt('information',"FrameElement [{$this->data->idFrameElement}] removido.", $go);
    }

}
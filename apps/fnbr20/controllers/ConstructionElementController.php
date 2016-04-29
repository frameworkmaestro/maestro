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

class ConstructionElementController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $ConstructionElement= new ConstructionElement($this->data->id);
        $filter->idConstructionElement = $this->data->idConstructionElement;
        $this->data->query = $ConstructionElement->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/ConstructionElement/save';
        $this->render();
    }

    public function formObject() {
        $this->data->ConstructionElement = ConstructionElement::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $ConstructionElement= new ConstructionElement($this->data->id);
        $this->data->ConstructionElement = $ConstructionElement->getData();
        
        $this->data->action = '@fnbr20/ConstructionElement/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $ConstructionElement = new ConstructionElement($this->data->id);
        $ok = '>fnbr20/ConstructionElement/delete/' . $ConstructionElement->getId();
        $cancelar = '>fnbr20/ConstructionElement/formObject/' . $ConstructionElement->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do ConstructionElement [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new ConstructionElement();
        $filter->idConstructionElement = $this->data->idConstructionElement;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $ConstructionElement = new ConstructionElement($this->data->ConstructionElement);
            $ConstructionElement->save();
            $go = '>fnbr20/ConstructionElement/formObject/' . $ConstructionElement->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $ConstructionElement = new ConstructionElement($this->data->id);
            $ConstructionElement->delete();
            $go = '>fnbr20/ConstructionElement/formFind';
            $this->renderPrompt('information',"ConstructionElement [{$this->data->idConstructionElement}] removido.", $go);
    }

}
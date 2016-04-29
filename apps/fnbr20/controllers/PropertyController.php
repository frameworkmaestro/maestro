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

class PropertyController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $property= new Property($this->data->id);
        $filter->idProperty = $this->data->idProperty;
        $this->data->query = $property->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/property/save';
        $this->render();
    }

    public function formObject() {
        $this->data->property = Property::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $property= new Property($this->data->id);
        $this->data->property = $property->getData();
        
        $this->data->action = '@fnbr20/property/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $property = new Property($this->data->id);
        $ok = '>fnbr20/property/delete/' . $property->getId();
        $cancelar = '>fnbr20/property/formObject/' . $property->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Property [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Property();
        $filter->idProperty = $this->data->idProperty;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $property = new Property($this->data->property);
            $property->save();
            $go = '>fnbr20/property/formObject/' . $property->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $property = new Property($this->data->id);
            $property->delete();
            $go = '>fnbr20/property/formFind';
            $this->renderPrompt('information',"Property [{$this->data->idProperty}] removido.", $go);
    }

}
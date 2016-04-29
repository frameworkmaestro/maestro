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

class ConstructionController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData(){
        $model = new Construction();
        $criteria = $model->listForLookupName($this->data->q);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formFind() {
        $Construction= new Construction($this->data->id);
        $filter->idConstruction = $this->data->idConstruction;
        $this->data->query = $Construction->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Construction/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Construction = Construction::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Construction= new Construction($this->data->id);
        $this->data->Construction = $Construction->getData();
        
        $this->data->action = '@fnbr20/Construction/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Construction = new Construction($this->data->id);
        $ok = '>fnbr20/Construction/delete/' . $Construction->getId();
        $cancelar = '>fnbr20/Construction/formObject/' . $Construction->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Construction [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Construction();
        $filter->idConstruction = $this->data->idConstruction;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Construction = new Construction($this->data->Construction);
            $Construction->save();
            $go = '>fnbr20/Construction/formObject/' . $Construction->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Construction = new Construction($this->data->id);
            $Construction->delete();
            $go = '>fnbr20/Construction/formFind';
            $this->renderPrompt('information',"Construction [{$this->data->idConstruction}] removido.", $go);
    }

}
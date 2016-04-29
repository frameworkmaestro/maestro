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

class LUController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData(){
        $model = new LU();
        $criteria = $model->listForLookup();
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formFind() {
        $LU= new LU($this->data->id);
        $filter->idLU = $this->data->idLU;
        $this->data->query = $LU->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/LU/save';
        $this->render();
    }

    public function formObject() {
        $this->data->LU = LU::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $LU= new LU($this->data->id);
        $this->data->LU = $LU->getData();
        $this->data->action = '@fnbr20/LU/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $LU = new LU($this->data->id);
        $ok = '>fnbr20/LU/delete/' . $LU->getId();
        $cancelar = '>fnbr20/LU/formObject/' . $LU->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do LU [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new LU();
        $filter->idLU = $this->data->idLU;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $LU = new LU($this->data->LU);
            $LU->save();
            $go = '>fnbr20/LU/formObject/' . $LU->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $LU = new LU($this->data->id);
            $LU->delete();
            $go = '>fnbr20/LU/formFind';
            $this->renderPrompt('information',"LU [{$this->data->idLU}] removido.", $go);
    }

}
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

class POSController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $POS= new POS($this->data->id);
        $filter->idPOS = $this->data->idPOS;
        $this->data->query = $POS->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/POS/save';
        $this->render();
    }

    public function formObject() {
        $this->data->POS = POS::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $POS= new POS($this->data->id);
        $this->data->POS = $POS->getData();
        
        $this->data->action = '@fnbr20/POS/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $POS = new POS($this->data->id);
        $ok = '>fnbr20/POS/delete/' . $POS->getId();
        $cancelar = '>fnbr20/POS/formObject/' . $POS->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do POS [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new POS();
        $filter->idPOS = $this->data->idPOS;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $POS = new POS($this->data->POS);
            $POS->save();
            $go = '>fnbr20/POS/formObject/' . $POS->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $POS = new POS($this->data->id);
            $POS->delete();
            $go = '>fnbr20/POS/formFind';
            $this->renderPrompt('information',"POS [{$this->data->idPOS}] removido.", $go);
    }

}
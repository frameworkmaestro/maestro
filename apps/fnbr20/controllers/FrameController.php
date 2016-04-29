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

class FrameController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData(){
        $model = new Frame();
        $criteria = $model->listForLookupName($this->data->q);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formFind() {
        $Frame= new Frame($this->data->id);
        $filter->idFrame = $this->data->idFrame;
        $this->data->query = $Frame->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Frame/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Frame = Frame::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Frame= new Frame($this->data->id);
        $this->data->Frame = $Frame->getData();
        
        $this->data->action = '@fnbr20/Frame/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Frame = new Frame($this->data->id);
        $ok = '>fnbr20/Frame/delete/' . $Frame->getId();
        $cancelar = '>fnbr20/Frame/formObject/' . $Frame->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Frame [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Frame();
        $filter->idFrame = $this->data->idFrame;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Frame = new Frame($this->data->Frame);
            $Frame->save();
            $go = '>fnbr20/Frame/formObject/' . $Frame->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Frame = new Frame($this->data->id);
            $Frame->delete();
            $go = '>fnbr20/Frame/formFind';
            $this->renderPrompt('information',"Frame [{$this->data->idFrame}] removido.", $go);
    }

}
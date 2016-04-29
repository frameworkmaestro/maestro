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

class GenericLabelController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $GenericLabel= new GenericLabel($this->data->id);
        $filter->idGenericLabel = $this->data->idGenericLabel;
        $this->data->query = $GenericLabel->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/GenericLabel/save';
        $this->render();
    }

    public function formObject() {
        $this->data->GenericLabel = GenericLabel::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $GenericLabel= new GenericLabel($this->data->id);
        $this->data->GenericLabel = $GenericLabel->getData();
        
        $this->data->action = '@fnbr20/GenericLabel/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $GenericLabel = new GenericLabel($this->data->id);
        $ok = '>fnbr20/GenericLabel/delete/' . $GenericLabel->getId();
        $cancelar = '>fnbr20/GenericLabel/formObject/' . $GenericLabel->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do GenericLabel [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new GenericLabel();
        $filter->idGenericLabel = $this->data->idGenericLabel;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $GenericLabel = new GenericLabel($this->data->GenericLabel);
            $GenericLabel->save();
            $go = '>fnbr20/GenericLabel/formObject/' . $GenericLabel->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $GenericLabel = new GenericLabel($this->data->id);
            $GenericLabel->delete();
            $go = '>fnbr20/GenericLabel/formFind';
            $this->renderPrompt('information',"GenericLabel [{$this->data->idGenericLabel}] removido.", $go);
    }

}
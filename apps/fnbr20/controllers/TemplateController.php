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

class TemplateController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function lookupData(){
        $model = new Template();
        $criteria = $model->listForLookup($this->data->id);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formFind() {
        $Template= new Template($this->data->id);
        $filter->idTemplate = $this->data->idTemplate;
        $this->data->query = $Template->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Template/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Template = Template::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Template= new Template($this->data->id);
        $this->data->Template = $Template->getData();
        
        $this->data->action = '@fnbr20/Template/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Template = new Template($this->data->id);
        $ok = '>fnbr20/Template/delete/' . $Template->getId();
        $cancelar = '>fnbr20/Template/formObject/' . $Template->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Template [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Template();
        $filter->idTemplate = $this->data->idTemplate;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Template = new Template($this->data->Template);
            $Template->save();
            $go = '>fnbr20/Template/formObject/' . $Template->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Template = new Template($this->data->id);
            $Template->delete();
            $go = '>fnbr20/Template/formFind';
            $this->renderPrompt('information',"Template [{$this->data->idTemplate}] removido.", $go);
    }

}
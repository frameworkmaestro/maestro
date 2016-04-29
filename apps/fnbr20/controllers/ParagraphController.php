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

class ParagraphController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Paragraph= new Paragraph($this->data->id);
        $filter->idParagraph = $this->data->idParagraph;
        $this->data->query = $Paragraph->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Paragraph/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Paragraph = Paragraph::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Paragraph= new Paragraph($this->data->id);
        $this->data->Paragraph = $Paragraph->getData();
        
        $this->data->action = '@fnbr20/Paragraph/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Paragraph = new Paragraph($this->data->id);
        $ok = '>fnbr20/Paragraph/delete/' . $Paragraph->getId();
        $cancelar = '>fnbr20/Paragraph/formObject/' . $Paragraph->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Paragraph [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Paragraph();
        $filter->idParagraph = $this->data->idParagraph;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Paragraph = new Paragraph($this->data->Paragraph);
            $Paragraph->save();
            $go = '>fnbr20/Paragraph/formObject/' . $Paragraph->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Paragraph = new Paragraph($this->data->id);
            $Paragraph->delete();
            $go = '>fnbr20/Paragraph/formFind';
            $this->renderPrompt('information',"Paragraph [{$this->data->idParagraph}] removido.", $go);
    }

}
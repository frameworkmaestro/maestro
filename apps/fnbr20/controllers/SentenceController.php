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

class SentenceController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Sentence= new Sentence($this->data->id);
        $filter->idSentence = $this->data->idSentence;
        $this->data->query = $Sentence->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Sentence/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Sentence = Sentence::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Sentence= new Sentence($this->data->id);
        $this->data->Sentence = $Sentence->getData();
        
        $this->data->action = '@fnbr20/Sentence/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Sentence = new Sentence($this->data->id);
        $ok = '>fnbr20/Sentence/delete/' . $Sentence->getId();
        $cancelar = '>fnbr20/Sentence/formObject/' . $Sentence->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Sentence [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Sentence();
        $filter->idSentence = $this->data->idSentence;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Sentence = new Sentence($this->data->Sentence);
            $Sentence->save();
            $go = '>fnbr20/Sentence/formObject/' . $Sentence->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Sentence = new Sentence($this->data->id);
            $Sentence->delete();
            $go = '>fnbr20/Sentence/formFind';
            $this->renderPrompt('information',"Sentence [{$this->data->idSentence}] removido.", $go);
    }

}
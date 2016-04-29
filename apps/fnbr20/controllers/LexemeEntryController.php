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

class LexemeEntryController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $LexemeEntry= new LexemeEntry($this->data->id);
        $filter->idLexemeEntry = $this->data->idLexemeEntry;
        $this->data->query = $LexemeEntry->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/LexemeEntry/save';
        $this->render();
    }

    public function formObject() {
        $this->data->LexemeEntry = LexemeEntry::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $LexemeEntry= new LexemeEntry($this->data->id);
        $this->data->LexemeEntry = $LexemeEntry->getData();
        
        $this->data->action = '@fnbr20/LexemeEntry/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $LexemeEntry = new LexemeEntry($this->data->id);
        $ok = '>fnbr20/LexemeEntry/delete/' . $LexemeEntry->getId();
        $cancelar = '>fnbr20/LexemeEntry/formObject/' . $LexemeEntry->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do LexemeEntry [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new LexemeEntry();
        $filter->idLexemeEntry = $this->data->idLexemeEntry;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $LexemeEntry = new LexemeEntry($this->data->LexemeEntry);
            $LexemeEntry->save();
            $go = '>fnbr20/LexemeEntry/formObject/' . $LexemeEntry->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $LexemeEntry = new LexemeEntry($this->data->id);
            $LexemeEntry->delete();
            $go = '>fnbr20/LexemeEntry/formFind';
            $this->renderPrompt('information',"LexemeEntry [{$this->data->idLexemeEntry}] removido.", $go);
    }

}
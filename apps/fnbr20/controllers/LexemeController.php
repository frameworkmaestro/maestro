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

class LexemeController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function gridLemmaData(){
        $model = new Lexeme();
        $filter = (object)['lexeme' => $this->data->id, 'language' => Manager::getContext()->get(1)];
        mdump($filter);
        $criteria = $model->listForGridLemma($filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formFind() {
        $Lexeme= new Lexeme($this->data->id);
        $filter->idLexeme = $this->data->idLexeme;
        $this->data->query = $Lexeme->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Lexeme/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Lexeme = Lexeme::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Lexeme= new Lexeme($this->data->id);
        $this->data->Lexeme = $Lexeme->getData();
        
        $this->data->action = '@fnbr20/Lexeme/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Lexeme = new Lexeme($this->data->id);
        $ok = '>fnbr20/Lexeme/delete/' . $Lexeme->getId();
        $cancelar = '>fnbr20/Lexeme/formObject/' . $Lexeme->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Lexeme [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Lexeme();
        $filter->idLexeme = $this->data->idLexeme;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Lexeme = new Lexeme($this->data->Lexeme);
            $Lexeme->save();
            $go = '>fnbr20/Lexeme/formObject/' . $Lexeme->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Lexeme = new Lexeme($this->data->id);
            $Lexeme->delete();
            $go = '>fnbr20/Lexeme/formFind';
            $this->renderPrompt('information',"Lexeme [{$this->data->idLexeme}] removido.", $go);
    }

}
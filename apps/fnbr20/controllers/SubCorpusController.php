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

class SubCorpusController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $SubCorpus= new SubCorpus($this->data->id);
        $filter->idSubCorpus = $this->data->idSubCorpus;
        $this->data->query = $SubCorpus->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/SubCorpus/save';
        $this->render();
    }

    public function formObject() {
        $this->data->SubCorpus = SubCorpus::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $SubCorpus= new SubCorpus($this->data->id);
        $this->data->SubCorpus = $SubCorpus->getData();
        
        $this->data->action = '@fnbr20/SubCorpus/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $SubCorpus = new SubCorpus($this->data->id);
        $ok = '>fnbr20/SubCorpus/delete/' . $SubCorpus->getId();
        $cancelar = '>fnbr20/SubCorpus/formObject/' . $SubCorpus->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do SubCorpus [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new SubCorpus();
        $filter->idSubCorpus = $this->data->idSubCorpus;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $SubCorpus = new SubCorpus($this->data->SubCorpus);
            $SubCorpus->save();
            $go = '>fnbr20/SubCorpus/formObject/' . $SubCorpus->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $SubCorpus = new SubCorpus($this->data->id);
            $SubCorpus->delete();
            $go = '>fnbr20/SubCorpus/formFind';
            $this->renderPrompt('information',"SubCorpus [{$this->data->idSubCorpus}] removido.", $go);
    }

}
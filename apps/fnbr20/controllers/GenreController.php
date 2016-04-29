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

class GenreController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Genre= new Genre($this->data->id);
        $filter->idGenre = $this->data->idGenre;
        $this->data->query = $Genre->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Genre/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Genre = Genre::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Genre= new Genre($this->data->id);
        $this->data->Genre = $Genre->getData();
        
        $this->data->action = '@fnbr20/Genre/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Genre = new Genre($this->data->id);
        $ok = '>fnbr20/Genre/delete/' . $Genre->getId();
        $cancelar = '>fnbr20/Genre/formObject/' . $Genre->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Genre [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Genre();
        $filter->idGenre = $this->data->idGenre;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Genre = new Genre($this->data->Genre);
            $Genre->save();
            $go = '>fnbr20/Genre/formObject/' . $Genre->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Genre = new Genre($this->data->id);
            $Genre->delete();
            $go = '>fnbr20/Genre/formFind';
            $this->renderPrompt('information',"Genre [{$this->data->idGenre}] removido.", $go);
    }

}
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

class AnnotationSetController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $AnnotationSet= new AnnotationSet($this->data->id);
        $filter->idAnnotationSet = $this->data->idAnnotationSet;
        $this->data->query = $AnnotationSet->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/AnnotationSet/save';
        $this->render();
    }

    public function formObject() {
        $this->data->AnnotationSet = AnnotationSet::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $AnnotationSet= new AnnotationSet($this->data->id);
        $this->data->AnnotationSet = $AnnotationSet->getData();
        
        $this->data->action = '@fnbr20/AnnotationSet/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $AnnotationSet = new AnnotationSet($this->data->id);
        $ok = '>fnbr20/AnnotationSet/delete/' . $AnnotationSet->getId();
        $cancelar = '>fnbr20/AnnotationSet/formObject/' . $AnnotationSet->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do AnnotationSet [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new AnnotationSet();
        $filter->idAnnotationSet = $this->data->idAnnotationSet;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $AnnotationSet = new AnnotationSet($this->data->AnnotationSet);
            $AnnotationSet->save();
            $go = '>fnbr20/AnnotationSet/formObject/' . $AnnotationSet->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $AnnotationSet = new AnnotationSet($this->data->id);
            $AnnotationSet->delete();
            $go = '>fnbr20/AnnotationSet/formFind';
            $this->renderPrompt('information',"AnnotationSet [{$this->data->idAnnotationSet}] removido.", $go);
    }

}
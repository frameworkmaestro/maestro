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

class TimelineController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Timeline= new Timeline($this->data->id);
        $filter->idTimeline = $this->data->idTimeline;
        $this->data->query = $Timeline->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Timeline/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Timeline = Timeline::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Timeline= new Timeline($this->data->id);
        $this->data->Timeline = $Timeline->getData();
        
        $this->data->action = '@fnbr20/Timeline/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Timeline = new Timeline($this->data->id);
        $ok = '>fnbr20/Timeline/delete/' . $Timeline->getId();
        $cancelar = '>fnbr20/Timeline/formObject/' . $Timeline->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Timeline [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Timeline();
        $filter->idTimeline = $this->data->idTimeline;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Timeline = new Timeline($this->data->Timeline);
            $Timeline->save();
            $go = '>fnbr20/Timeline/formObject/' . $Timeline->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Timeline = new Timeline($this->data->id);
            $Timeline->delete();
            $go = '>fnbr20/Timeline/formFind';
            $this->renderPrompt('information',"Timeline [{$this->data->idTimeline}] removido.", $go);
    }

}
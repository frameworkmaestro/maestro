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

class TypeController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $Type= new Type($this->data->id);
        $filter->idType = $this->data->idType;
        $this->data->query = $Type->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/Type/save';
        $this->render();
    }

    public function formObject() {
        $this->data->Type = Type::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $Type= new Type($this->data->id);
        $this->data->Type = $Type->getData();
        
        $this->data->action = '@fnbr20/Type/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $Type = new Type($this->data->id);
        $ok = '>fnbr20/Type/delete/' . $Type->getId();
        $cancelar = '>fnbr20/Type/formObject/' . $Type->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Type [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Type();
        $filter->idType = $this->data->idType;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $Type = new Type($this->data->Type);
            $Type->save();
            $go = '>fnbr20/Type/formObject/' . $Type->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Type = new Type($this->data->id);
            $Type->delete();
            $go = '>fnbr20/Type/formFind';
            $this->renderPrompt('information',"Type [{$this->data->idType}] removido.", $go);
    }

}
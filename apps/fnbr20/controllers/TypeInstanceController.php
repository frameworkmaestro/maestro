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

class TypeInstanceController extends MController {

    public function main() {
        $this->render("formBase");
    }
    
    public function lookupCoreType() {
        $model = new TypeInstance();
        $result = $model->listCoreType()->asQuery()->getResult(\FETCH_ASSOC);
        $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function formFind() {
        $TypeInstance= new TypeInstance($this->data->id);
        $filter->idTypeInstance = $this->data->idTypeInstance;
        $this->data->query = $TypeInstance->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@fnbr20/TypeInstance/save';
        $this->render();
    }

    public function formObject() {
        $this->data->TypeInstance = TypeInstance::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $TypeInstance= new TypeInstance($this->data->id);
        $this->data->TypeInstance = $TypeInstance->getData();
        
        $this->data->action = '@fnbr20/TypeInstance/save/' .  $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $TypeInstance = new TypeInstance($this->data->id);
        $ok = '>fnbr20/TypeInstance/delete/' . $TypeInstance->getId();
        $cancelar = '>fnbr20/TypeInstance/formObject/' . $TypeInstance->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do TypeInstance [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new TypeInstance();
        $filter->idTypeInstance = $this->data->idTypeInstance;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
            $TypeInstance = new TypeInstance($this->data->TypeInstance);
            $TypeInstance->save();
            $go = '>fnbr20/TypeInstance/formObject/' . $TypeInstance->getId();
            $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $TypeInstance = new TypeInstance($this->data->id);
            $TypeInstance->delete();
            $go = '>fnbr20/TypeInstance/formFind';
            $this->renderPrompt('information',"TypeInstance [{$this->data->idTypeInstance}] removido.", $go);
    }

}
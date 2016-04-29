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

Manager::import("fnbr20\models\Base");
Manager::import("auth\models\*");

class PersonController extends MController {

    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'auth/person/gridData');
        $this->render();
    }
    
    public function lookupData(){
        $model = new Person();
        $criteria = $model->listForLookup();
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function gridData()
    {
        $model = new Person();
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formObject()
    {
        $model = new Person($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Person");
        $this->data->save = "@fnbr20/auth/person/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/auth/person/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new Person($this->data->id);
            $model->setData($this->data->person);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $model = new Person($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', _M("Deletion denied."));
        }
    }

}
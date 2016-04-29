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

class LanguageController extends MController
{

    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'language/gridData');
        $this->render();
    }

    public function gridData()
    {
        $model = new Language($this->data->id);
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function comboData()
    {
        $model = new Language($this->data->id);
        $criteria = $model->listForCombo();
        $this->renderJSON($model->gridDataAsJSON($criteria, true));
    }

    public function formObject()
    {
        $model = new Language($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Language");
        $this->data->save = "@fnbr20/language/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/language/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new Language($this->data->id);
            $model->setData($this->data);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $model = new Language($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}

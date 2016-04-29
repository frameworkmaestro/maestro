<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class TypeController extends MController
{
    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'admin/type/gridData');
        $this->render();
    }
    
    public function gridData()
    {
        $model = new Type();
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formObject()
    {
        $model = new Type($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Type");
        $this->data->save = "@fnbr20/admin/type/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/admin/type/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new Type();
            $this->data->type->entry = 'typ_' . $this->data->type->entry;
            $model->setData($this->data->type);
            $model->save();
            $this->renderPrompt('information', 'OK', "editEntry('{$this->data->type->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
}

<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class RelationGroupController extends MController
{
    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'admin/relationgroup/gridData');
        $this->render();
    }
    
    public function gridData()
    {
        $model = new RelationGroup();
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formObject()
    {
        $model = new RelationGroup($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Relation Group");
        $this->data->save = "@fnbr20/admin/relationgroup/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/admin/relationgroup/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new RelationGroup();
            $this->data->relationgroup->entry = 'rgp_' . $this->data->relationgroup->entry;
            $model->setData($this->data->relationgroup);
            $model->save();
            $this->renderPrompt('information', 'OK', "editEntry('{$this->data->relationgroup->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
}

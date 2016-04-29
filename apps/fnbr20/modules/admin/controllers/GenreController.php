<?php

use Maestro\MVC\MApp;

Manager::import("fnbr20\models\*");

class GenreController extends MController
{
    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'admin/genre/gridData');
        $this->render();
    }
    
    public function gridData()
    {
        $model = new Genre();
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formObject()
    {
        $model = new Genre($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Genre");
        $this->data->save = "@fnbr20/admin/genre/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/admin/genre/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new Genre();
            $this->data->genre->entry = 'gen_' . $this->data->genre->entry;
            $model->setData($this->data->genre);
            $model->save();
            $this->renderPrompt('information', 'OK', "editEntry('{$this->data->genre->entry}');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
}

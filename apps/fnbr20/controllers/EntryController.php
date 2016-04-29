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

class EntryController extends MController {

    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'entry/gridData');
        $this->render();
    }

    public function gridData()
    {
        $model = new Entry($this->data->id);
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formObject()
    {
        $model = new Entry($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New Entry");
        $this->data->save = "@fnbr20/entry/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/entry/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function formUpdate()
    {
        $model = new Entry();
        $this->data->undefined = $model->getUndefinedLanguages($this->data->id);
        $this->data->new = "@fnbr20/entry/newLanguage/" . $this->data->id;
        $this->data->title = "Entry: " . $this->data->id;
        $this->data->query = Manager::getAppURL('fnbr20', 'entry/gridUpdateData/' . $this->data->id);
        $this->render();
    }

    public function gridUpdate()
    {
        $this->data->title = "Entry: " . $this->data->id;
        $this->data->query = Manager::getAppURL('fnbr20', 'entry/gridUpdateData/' . $this->data->id);
        $this->render();
    }

    public function gridUpdateData()
    {
        $model = new Entry();
        $filter = (object)[
            'entry' => $this->data->id
        ];
        $criteria = $model->listForUpdate($filter);
        mdump($criteria->asQuery()->getResult());
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formUpdateEntry()
    {
        $model = new Entry($this->data->id);
        $this->data->object = $model->getData();
        $this->data->title = $model->getEntry() . ' [' . $model->getLanguage()->getLanguage() . ']';
        $this->data->save = "@fnbr20/entry/save/" . $model->getId() . '|formUpdateEntry';
        $this->render();
    }

    public function newLanguage()
    {
        try {
            $model = new Entry();
            $model->addLanguage($this->data->id, $this->data->idLanguage);
            $this->renderPrompt('information', 'OK', "reloadGridUpdateEntry();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function save()
    {
        try {
            $model = new Entry($this->data->id);
            $model->setData($this->data->entry);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function delete()
    {
        try {
            $model = new Entry($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function lookup()
    {
        $model = new Language();
        $this->data->language = $this->data->lookupLanguage;
        $criteria = $model->listByFilter($this->data);
        $this->renderJSON($model->gridDataAsJSON($criteria, true));
    }

}
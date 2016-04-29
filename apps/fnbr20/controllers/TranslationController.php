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

class TranslationController extends MController {

    public function main()
    {
        $this->data->query = Manager::getAppURL('fnbr20', 'translation/gridData');
        $this->render();
    }

    public function gridData()
    {
        $model = new Transalation($this->data->id);
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
        $this->data->title = "Translation: " . $this->data->id;
        $this->data->query = Manager::getAppURL('fnbr20', 'translation/gridUpdateData/' . $this->data->id);
        $this->render();
    }

    public function gridUpdateData()
    {
        $model = new Translation();
        $filter = (object)[
            'resource' => $this->data->id
        ];
        $criteria = $model->listForUpdate($filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formUpdateTranslation()
    {
        $model = new Translation($this->data->id);
        $this->data->object = $model->getData();
        $this->data->title = $model->getResource();
        $this->data->language = $model->getLanguage()->getLanguage();
        $this->data->close = "!$('#formUpdateTranslation_dialog').dialog('close');";        
        $this->data->save = "@fnbr20/translation/save/" . $model->getId() . '|formUpdateTranslation';
        $this->render();
    }

    public function save()
    {
        try {
            $model = new Translation($this->data->id);
            $model->setData($this->data->translation);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $model = new Translation($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }


}
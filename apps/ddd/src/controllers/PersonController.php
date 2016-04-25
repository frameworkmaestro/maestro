<?php

namespace ddd\controllers;

class PersonController extends \MController
{
    private $personService;

    public function services(\ddd\services\personService $personService)
    {
        $this->personService = $personService;
    }

    public function main()
    {
        $this->render();
    }

    public function lookupData()
    {
        $this->data->name = $this->data->q;
        $this->renderJSON($this->personService->dataForLookup($this->data));
    }

    public function tree()
    {
        $children = $this->personService->listPersons($this->data);
        if ($this->data->id == '') {
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Persons',
                'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id == 'root') {
            $json = json_encode($children);
        }
        $this->renderJson($json);
    }

    public function formNew()
    {
        $this->data->title = _M('New Person');
        $this->render();
    }

    public function formUpdate()
    {
        $this->data->object = $this->personService->retrieve($this->data->id);
        $this->data->title = 'Person: ' . $this->data->object->name;
        $this->render();
    }

    public function formDelete()
    {
        $ok = "^mknob/structure/level/actionDelete/" . $this->data->id;
        $this->renderPrompt('confirmation', 'Warning: Level will be removed! Continue?', $ok);
    }

    public function gridData()
    {
        $structure = MApp::getService('mknob', '', 'structurelevel');
        $dataForGrid = $structure->gridData($this->data->filter);
        $this->renderJSON($dataForGrid);
    }

    public function actionNew()
    {
        try {
            $this->personService->create($this->data->person);
            $this->renderPrompt('information', 'Record created.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function actionUpdate()
    {
        try {
            $this->personService->update($this->data->person);
            $this->renderPrompt('information', 'Record updated.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function actionDelete()
    {
        try {
            $structure = MApp::getService('mknob', '', 'structurelevel');
            $structure->delete($this->data->id);
            $this->renderPrompt('information', 'OK', "!structure.reloadParent();");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }

    }

}

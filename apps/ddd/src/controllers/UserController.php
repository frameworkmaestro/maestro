<?php

namespace ddd\controllers;

class UserController extends \MController
{
    private $userService;

    public function services(\ddd\services\UserService $userService) {
        $this->userService = $userService;
    }

    public function main()
    {
        $this->render();
    }

    public function tree()
    {
        $children = $this->userService->listUsers($this->data);
        if ($this->data->id == '') {
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Users',
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
        $this->data->title = _M('New Level');
        $this->render();
    }

    public function formUpdate()
    {
        $this->data->object = $this->userService->retrieve($this->data->id);
        $this->data->title = 'User: ' . $this->data->object->login;
        mdump($this->data);
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
            $this->userService->create($this->data->user);
            $this->renderPrompt('information', 'Record created.');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function actionUpdate()
    {
        try {
            $this->userService->update($this->data->user);
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

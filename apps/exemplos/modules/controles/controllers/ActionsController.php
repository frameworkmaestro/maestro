<?php

use Maestro\Services\MJSON;

class ActionsController extends MController
{

    public function main()
    {
        $this->render();
    }

    public function formTeste()
    {
        $this->render();
    }

    public function actions()
    {
        $this->render();
    }

    public function formActions()
    {
        $this->render();
    }

    public function formActionsPost()
    {
        $this->renderPrompt('information', 'Action executada via POST.');
    }

    public function formButtons()
    {
        $this->render();
    }

    public function formButtonsPost()
    {
        $this->renderPrompt('information', 'Action executada via POST.');
    }

    public function ajaxButtons()
    {
        $this->render();
    }

    public function ajaxJson()
    {
        $objeto = (object) [
                    'id' => '1',
                    'nome' => 'Teste JSON',
                    'numero' => 65,
                    'boleano' => false
        ];
        $this->renderJSON(MJSON::encode($objeto));
    }

    public function formLinks()
    {
        $this->render();
    }

    public function formEvent()
    {
        $this->render();
    }

    public function formJavascript()
    {
        $this->render();
    }

    public function formTool()
    {
        $this->render();
    }

}

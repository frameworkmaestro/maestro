<?php

use exemplos\models as models;

class ServicosController extends MController {

    public function formNovoAluno() {
        $this->data->actionURL = "@exemplos/services/data/createAluno";
        $this->data->actionController = "@exemplos/servicos/novoAluno";
        $this->render();
    }

    public function novoAluno() {
        $service = $this->getService('data');
        $mensagem = $service->novoAluno();
        $this->renderPrompt('information', $mensagem);
    }

}

?>
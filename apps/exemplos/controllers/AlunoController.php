<?php

Manager::import("exemplos\models\*");

class AlunoController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new Aluno($this->data->id);
        $this->data->object = $model->getData();
        $this->data->query = $model->listByFilter($this->data->filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $this->data->action = '@exemplos/aluno/save';
        $this->render();
    }

    public function formNewLookup() {
        $this->data->action = '@exemplos/aluno/save';
        $this->render();
    }

    public function formObject() {
        $this->data->object = Aluno::create($this->data->id)->getData();
        $this->render();
    }

    public function formUpdate() {
        $this->data->object = Aluno::create($this->data->id)->getData();
        $this->data->action = '@exemplos/aluno/save/' . $this->data->id;
        $this->render();
    }

    public function formDelete() {
        $model = new Aluno($this->data->id);
        $ok = '>exemplos/aluno/delete/' . $model->getId();
        $cancelar = '>exemplos/aluno/formObject/' . $model->getId();
        $this->renderPrompt('confirmation', "Confirma remoção do Aluno [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new Aluno();
        $filter->matricula = $this->data->matricula;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        /* Como Aluno herda de Pessoa, é necessário um tratamento específico */
        try {
            $model = new Aluno($this->data);
            $model->save();
            $go = '>exemplos/aluno/formObject/' . $model->getId();
            $this->renderPrompt('information', 'OK', $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new Aluno($this->data->id);
            $model->delete();
            $go = '>exemplos/aluno/formFind';
            $this->renderPrompt('information', "Aluno [{$this->data->matricula}] removido.", $go);
        } catch (EControllerException $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
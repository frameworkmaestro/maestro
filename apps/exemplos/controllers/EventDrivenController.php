<?php

Manager::import('exemplos\models\*');

class EventDrivenController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new Pessoa($this->data->id);
        $filter->nome = $this->data->nome . '%';
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function formNew() {
        $pessoa = new Pessoa($this->data->id);
        $this->data->options = $pessoa->listByFilter($filter)->asQuery()->chunkResult(0, 1);
        $this->render();
    }

    public function formObject() {
        $model = new Pessoa($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new Pessoa($this->data->id);
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formDelete() {
        $ok = '>exemplos/pessoa/delete/' . $this->data->id;
        $cancelar = '>exemplos/pessoa/formObject/' . $this->data->id;
        $this->renderPrompt('confirmation', "Confirma remoção do registro {$this->data->id}?", $ok, $cancelar);
    }

    public function formNewWindow() {
        $this->render();
    }

    public function formNewWindowPost() {
        $pessoa = new models\Pessoa($this->data->idPessoa);
        $pessoa->setData($this->data);
        $pessoa->save();
        $this->data->object = $pessoa->getData();
        mdump($this->data->object);
        $this->render();
    }

    public function formJSON() {
        $this->render();
    }

    public function formFoto() {
        $pessoa = new Pessoa($this->data->id);
        $this->data->object = $pessoa->getData();
        $this->data->url = $this->data->object->foto->getUrl();
        $this->render();
    }

    public function lookup() {
        $model = new models\Pessoa();
        $filter->nome = $this->data->filter0;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new Pessoa($this->data->id);
            //$model = Pessoa::create($this->data);
            $model->setData($this->data);
            $model->save();
            $go = '>exemplos/pessoa/formObject/' . $model->getId();
            $this->renderPrompt('information', 'OK', $go);
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function saveWindow() {
        try {
            $pessoa = new models\Pessoa($this->data->id);
            $pessoa->setData($this->data);
            $pessoa->save();
            $this->renderPrompt('information', 'Dados gravados com sucesso.', "!getByJSON({$pessoa->getIdPessoa()});");
        } catch (\Exception $e) {
            $this->renderPrompt(\MPrompt::error($e->getMessage()));
        }
    }

    public function delete() {
        try {
            $model = new models\pessoa($this->data->id);
            $model->delete();
            $go = '>exemplos/pessoa/formFind';
            $this->renderPrompt('information', "Registro {$this->data->id} removido.", $go);
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function saveFoto() {
        try {
            $pessoa = new models\pessoa($this->data->id);
            $pessoa->setFoto(Mutil::parseFiles('foto', 0));
            $pessoa->save();
            $go = '>exemplos/pessoa/formObject/' . $this->data->id;
            $this->renderPrompt('information', 'OK', $go);
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
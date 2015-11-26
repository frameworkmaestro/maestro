<?php

// Importa os models e cria aliases para cada classe
Manager::import('crud\models\*');

class PessoaController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        // 'query' pode ser um objeto MQuery ou a URL da action para se obter os dados via JSON
        $this->data->query = Manager::getAppURL('exemplos','crud/pessoa/formFindData');
        $this->render();
    }

    public function formFindData() {
        $model = new Pessoa($this->data->id);
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formNew() {
        // Exemplo de uso de "options" para MSelection
        $this->data->options = array(
            'RJ' => 'Rio de Janeiro',
            'MG' => 'Minas Gerais',
            'SP' => 'São Paulo',
            'ES' => 'Espírito Santo',
            'BA' => 'Bahia',
            'RS' => 'Rio Grande do Sul'
        );
        // Exemplo de como processar ou não um campo do formulário
        $this->data->process = true;
        // Definição da action para o botão de POST
        $this->data->action = "@exemplos/crud/pessoa/save";
        $this->render();
    }

    public function formObject() {
        // formObject define as opções de ação, quando um registro específico foi selecionado
        $model = new Pessoa($this->data->id);
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $model->getData();
        $this->render();
    }

    public function formUpdate() {
        $model = new Pessoa($this->data->id);
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $model->getData();
        // Exemplo de como processar ou não um campo do formulário
        $this->data->process = false;
        // Definição da action para o botão de POST
        $this->data->action = "@exemplos/crud/pessoa/save/" . $model->getId();;
        $this->render();
    }

    public function formFoto() {
        $pessoa = new Pessoa($this->data->id);
        // obtém o código binário da foto armazenada no DB e salva em um arquivo temporário
        $pessoa->getFoto()->saveToCache();
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $pessoa->getData();
        // a foto é representada pela URL do arquivo temporário
        $this->data->url = $this->data->object->foto;
        $this->render();
    }

    public function formDelete() {
        $model = new Pessoa($this->data->id);
        $ok = '>exemplos/crud/pessoa/delete/' . $model->getId();
        $cancelar = "close";
        // Apresenta um prompt para confirmar a exclusão do registro
        $this->renderPrompt('confirmation', "Confirma remoção de {$model->getDescription()}?", $ok, $cancelar);
    }

    public function formMail() {
        $pessoa = new Pessoa($this->data->id);
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $pessoa->getData();
        // Definição da action para o botão de POST
        $this->data->action = '@exemplos/crud/pessoa/sendEmail';
        $this->render();
    }

    public function sendEmail() {
        // exemplo de uso de uma extensão (PHPMailer)
        try {
            $time = Manager::getSysTime();
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = Manager::getConf('mailer.smtpServer'); // SMTP server
            $mail->From = Manager::getConf('mailer.smtpFrom');
            $mail->FromName = Manager::getConf('mailer.smtpFromName');
            $mail->Subject = $this->data->assunto;
            $mail->isHTML(false);
            $mail->CharSet = 'utf-8';
            $body = 'Enviada de: ' . $ipaddress . ' em ' . $time;
            $mail->Body = $body . "\n" . $this->data->mensagem;
            $mail->WordWrap = 100;
            $mail->addAddress($this->data->email);
            $ok = $mail->send();
            $mail->clearAllRecipients();
            $this->renderPrompt('information', 'Mensagem enviada com sucesso!');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function save() {
        try {
            $model = new Pessoa($this->data->id);
            $model->setData($this->data);
            $model->save();
            $go = ">exemplos/crud/pessoa/formObject/{$model->getId()}|formAreaBase";
            $this->renderPrompt('information', 'OK', $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function saveFoto() {
        try {
            $pessoa = new Pessoa($this->data->id);
            $pessoa->setFoto(\Maestro\Utils\Mutil::parseFiles('novaFoto', 0));
            $pessoa->save();
            $this->renderPrompt('information', 'OK',">exemplos/crud/pessoa/formFoto/{$this->data->id}|formAreaObject");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new Pessoa($this->data->id);
            $model->delete();
            $go = '>exemplos/crud/pessoa/main';
            $this->renderPrompt('information', "Registro {$this->data->id} removido.", $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function lookupNome(){
        mdump("action doLookupNome");
        $model = new Pessoa();
        $this->data->nome = $this->data->lookupPessoa;
        $criteria = $model->listByFilter($this->data);
        mdump($this->data);
        $this->renderJSON($model->gridDataAsJSON($criteria, true));
    }

}
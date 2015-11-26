<?php

// Importa os models e cria aliases para cada classe
Manager::import('crud\models\*');

class AlunoController extends MController {

    public function main() {
        // URL da action para se obter os dados via JSON
        $this->data->url = Manager::getAppURL('exemplos','crud/aluno/gridData');
        $this->render();
    }

    public function gridData() {
        $model = new Aluno();
        $criteria = $model->listByFilter($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formNew() {
        // Definição da action para o botão de POST
        $this->data->action = "@exemplos/crud/aluno/save";
        $this->render();
    }

    public function formUpdate() {
        // Obtem o id da linha selecionada no grid
        $this->data->id = $this->data->gridMainAluno_data->idValue;
        $model = new Aluno($this->data->id);
        // 'object' é um plain object, com os dados do registro
        $this->data->object->aluno = $model->getData();
        // Definição da action para o botão de POST
        $this->data->action = "@exemplos/crud/aluno/save/" . $model->getId();;
        $this->renderDialog();
    }
    
    public function formDelete() {
        // Obtem o id da linha selecionada no grid
        $this->data->id = $this->data->gridMainAluno_data->idValue;
        $model = new Aluno($this->data->id);
        $ok = '>exemplos/crud/aluno/delete/' . $model->getId();
        $cancelar = "close";
        // Apresenta um prompt para confirmar a exclusão do registro
        $this->renderPrompt('confirmation', "Confirma remoção de {$model->getDescription()}?", $ok, $cancelar);
    }
    
    public function formFoto() {
        // Obtem o id da linha selecionada no grid
        $this->data->id = $this->data->gridMainAluno_data->idValue;
        $model = new Aluno($this->data->id);
        // obtém o código binário da foto armazenada no DB e salva em um arquivo temporário
        $model->getFoto()->saveToCache();
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $model->getData();
        // a foto é representada pela URL do arquivo temporário
        $this->data->url = $this->data->object->foto;
        $this->renderDialog();
    }

    public function save() {
        try {
            $model = new Aluno($this->data->aluno->idAluno);
            $model->setData($this->data->aluno);
            $model->save();
            $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }
    
    public function delete() {
        try {
            $model = new Aluno($this->data->id);
            $model->delete();
            $go = "!jQuery('#gridMainAluno').mgrid('reload')";
            $this->renderPrompt('information', "Registro {$this->data->id} removido.", $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    //---
    public function formObject() {
        // formObject define as opções de ação, quando um registro específico foi selecionado
        $model = new Pessoa($this->data->id);
        // 'object' é um plain object, com os dados do registro
        $this->data->object = $model->getData();
        $this->render();
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


    public function saveFoto() {
        try {
            $pessoa = new Pessoa($this->data->id);
            $pessoa->setFoto(\Maestro\Utils\Mutil::parseFiles('novaFoto', 0));
            $pessoa->save();
            $this->renderPrompt('information', 'OK',">crud/pessoa/formFoto/{$this->data->id}|formAreaObject");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }


}
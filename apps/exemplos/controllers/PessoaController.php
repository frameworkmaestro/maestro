<?php

Manager::import('exemplos\models\*');

class PessoaController extends MController {

    public function main() {
        $this->render("formBase");
    }

    public function formFind() {
        $model = new Pessoa($this->data->id);
        $this->data->object = $model->getData();
        $filter->nome = $this->data->nome . '%';
        $this->data->query = $model->listByFilter($filter)->asQuery();
        mdump($this->data->query->getResult());
        mdump($this->data->query->getColumnNames());
        $this->render();
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
        $this->data->action = "@exemplos/pessoa/save";
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
        $this->data->action = "@exemplos/pessoa/save/" . $model->getId();;
        $this->render();
    }

    public function formDelete() {
        $model = new Pessoa($this->data->id);
        $ok = '>exemplos/pessoa/delete/' . $model->getId();
        $cancelar = '>exemplos/pessoa/formObject/' . $model->getId();
        $this->renderPrompt('confirmation', "Confirma remoção de {$model->getDescription()}?", $ok, $cancelar);
    }

    public function formNewWindow() {
        $this->render();
    }

    public function formNewWindowPost() {
        $pessoa = new models\Pessoa($this->data->idPessoa);
        $pessoa->setData($this->data);
        $pessoa->save();
        $this->data->object = $pessoa->getData();
        $this->render();
    }

    public function formJSON() {
        $this->render();
    }

    public function formFoto() {
        $pessoa = new Pessoa($this->data->id);
        $pessoa->getFoto()->saveToCache();
        $this->data->object = $pessoa->getData();
        $this->data->url = $this->data->object->foto;
        $this->render();
    }

    public function formMail() {
        $pessoa = new Pessoa($this->data->id);
        $this->data->object = $pessoa->getData();
        $this->data->action = '@pessoa/sendEmail';
        $this->render();
    }

    public function sendEmail() {
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
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function lookup() {
        $model = new Pessoa();
        $this->data->query = $model->listByFilter($this->data->filter)->asQuery();
        $this->render();
    }

    public function save() {
        try {
            $model = new Pessoa($this->data->id);
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
            $pessoa = new Pessoa($this->data->id);
            $pessoa->setFoto(Mutil::parseFiles('foto', 0));
            $pessoa->save();
            $go = '>exemplos/pessoa/formObject/' . $this->data->id;
            $this->renderPrompt('information', 'OK', $go);
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

 public function deleteFullGrid() {
        try {
            $model = new models\pessoa($this->data->id);
            $model->delete();
            $go = '>exemplos/controls/formFullGrid';
            $this->renderPrompt('information', "Registro {$this->data->id} removido.", $go);
        } catch (Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}
<?php

Manager::import("exemplos\models\*");

class DataService extends MService {

    /**
     * Exemplo de implementação da lógica da aplicação via serviço.
     * - Este serviço coloca o banco em estado de transação, cria uma Pessoa, cria
     * um Aluno referente a esta Pessoa e envia um email notificando a criação
     * do registro. 
     * - Os dados de entrada chegam via $this->data
     */
    public function createAluno() {
        $db = $this->getDatabase('exemplos');
        try {
            $transaction = $db->beginTransaction();
            $pessoa = new Pessoa();
            $pessoa->setData($this->data);
            $pessoa->save();
            $this->data->idPessoa = $pessoa->getId();
            $aluno = new Aluno();
            $aluno->setData($this->data);
            $aluno->save();
            $mail = $this->getMail();
            $mail->Subject = 'Novo registro de aluno  - ' . $aluno->getMatricula();
            $mail->Body = 'Criado novo registro de aluno  - ' . $aluno->getMatricula() . ' em ' . Manager::getSysTime();
            $mail->addAddress($this->data->email);
            $ok = $mail->send();
            $this->data->result = 'Aluno criado com sucesso.';
            $transaction->commit();
        } catch (\Exception $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            $this->data->error = true;
            $this->data->result = $e->getMessage();
        }
        $this->renderJSON();
    }

    public function novoAluno() {
        $db = $this->getDatabase('exemplos');
        try {
            $transaction = $db->beginTransaction();
            $pessoa = new Pessoa();
            $pessoa->setData($this->data);
            $pessoa->save();
            $this->data->idPessoa = $pessoa->getId();
            $aluno = new Aluno();
            $aluno->setData($this->data);
            $aluno->save();
            $mail = $this->getMail();
            $mail->Subject = 'Novo registro de aluno  - ' . $aluno->getMatricula();
            $mail->Body = 'Criado novo registro de aluno  - ' . $aluno->getMatricula() . ' em ' . Manager::getSysTime();
            $mail->addAddress($this->data->email);
            $ok = $mail->send();
            $mensagem = 'Aluno criado com sucesso.';
            $transaction->commit();
        } catch (\Exception $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            $this->data->error = true;
            $mensagem = $e->getMessage();
        }
        return $mensagem;
    }
    /**
     * Exemplo de serviço a ser usado por programas offline.
     */
    public function dadosPessoaByNome() {
        try {
            $db = $this->getDatabase('exemplos');
            try {
                $transaction = $db->beginTransaction();
                $pessoa = new Pessoa();
                $this->data->result = $pessoa->listByFilter($this->data)->asQuery()->getResult();
                $transaction->commit();
            } catch (\Exception $e) {
                // rollback da transação em caso de algum erro
                $transaction->rollback();
                $this->data->error = true;
                $this->data->result = $e->getMessage();
            }
        } catch (\Exception $e) {
            $this->data->error = true;
            $this->data->result = $e->getMessage();
        }
        $this->renderJSON();
    }

}
<?php

class DiversosController extends MController {

    public function formBackground() {
        $this->render();
    }

    public function formBackgroundExecute() {
        $this->data->background = Manager::getURL('diversos/background');
        $this->render();
    }

    public function background() {
        $this->prepareFlush();
        for($i=1;$i < 15;$i++){
            $this->data->idContainer = 'container'.$i;
            $this->data->message = 'Teste'.$i."\n";
            $this->renderFlush();
// alternativa para output de texto puro: $this->flush($this->data->message);
            sleep(1);
        }
    }
    
    public function formEmail() 
    {
        if(\Manager::getConf('mailer.smtpFrom'))
        {
            $this->data->desricaoConfFrom = '(' . \Manager::getConf('mailer.smtpFrom') . ')';
        }
        if(\Manager::getConf('mailer.smtpTo'))
        {
            $this->data->desricaoConfTo = '(' . \Manager::getConf('mailer.smtpTo') . ')';
        }
        
        $this->render();
    }
    
    public function formEmailEnviar()
    {
        // Os parametros dos destinatarios (to, cc e bcc) podem ser um array ou uma lista de emails separados por vírgula
        $params->to = $this->data->destinatario;
        $params->cc = $this->data->cc;
        $params->bcc = $this->data->bcc;
        
        $params->Subject = $this->data->assunto;
        
        $params->Body = $this->data->corpo;
        
        //Para a formatação com HTML
        $params->isHTML = true;
        
        // Remetente padrao \Manager::getConf('mailer.smtpFrom')
        if($this->data->remetente)
        {
            $params->From = $this->data->remetente;
        }
        
        $success = MMailer::send($params);
        
        if($success)
        {
            $this->renderPrompt('information', "Email enviado com sucesso");
        }
        else
        {
            $this->renderPrompt('error', "Tentativa de envio de email falhou: {$mailer->ErrorInfo}");
        }
    }
    
}

?>
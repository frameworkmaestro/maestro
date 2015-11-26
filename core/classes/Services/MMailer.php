<?php

namespace Maestro\Services;

use Maestro\Manager;

class MMailer {

    /**
     * 
     * @param stdClass $params
     * @return \PHPMailer
     */
    public static function getMailer($params = null) {
        $mailer = new \PHPMailer();

        $mailer->IsSMTP(); // telling the class to use SMTP
        $mailer->Host = \Manager::getConf('mailer.smtpServer'); // SMTP server
        $mailer->From = $params->from ? : \Manager::getConf('mailer.smtpFrom');
        $mailer->FromName = $params->fromName ?: \Manager::getConf('mailer.smtpFromName');
        $mailer->Subject = $params->subject;
        $mailer->Body = $params->body;
        $mailer->CharSet = 'utf-8';
        $mailer->WordWrap = $params->wordWrap ? : 100;
        $auth = \Manager::getConf('mailer.smtpAuth');
        if ($auth) {
            $mailer->SMTPAuth = true;                     // Usa autenticação SMTP
            $mailer->SMTP_PORT = \Manager::getConf('mailer.smtpPort');                    // Porta do servidor SMTP
            $mailer->Username = \Manager::getConf('mailer.smtpFrom'); // Usuário do servidor SMTP
            $mailer->Password = \Manager::getConf('mailer.smtpPass');                // Senha do servidor SMTP
        }

        // Caso não exista destinatário, 
        // o destinatário passa a ser o email configurado no conf
        if (!self::hasReceivers($params)) {
            $params->to = $params->cc = $params->bcc = \Manager::getConf('mailer.smtpTo');
        }

        // Preenche os parametros do mailer. Ver atributos publicos da classe PHPMailer
        self::copyPublicAttributes($params, $mailer);

        $mailer->isHTML($params->isHTML);

        // Preenche os destinatários
        $to = self::emailListToArray($params->to);
        $cc = self::emailListToArray($params->cc);
        $bcc = self::emailListToArray($params->bcc);

        foreach ($to as $address) {
            $mailer->AddAddress($address);
        }
        foreach ($cc as $address) {
            $mailer->AddCC($address);
        }
        foreach ($bcc as $address) {
            $mailer->AddBCC($address);
        }

        return $mailer;
    }

    protected static function copyPublicAttributes($from, $to) {
        $publicFromAttributes = get_object_vars($from);
        $publicToAttributes = get_object_vars($to);
        $commonPublicAttributes = array_intersect_key($publicFromAttributes, $publicToAttributes);
        foreach ($commonPublicAttributes as $attributeName => $attributeValue) {
            $to->$attributeName = $attributeValue;
        }
    }

    protected static function hasReceivers($params) {
        return !(empty($params->to) && empty($params->cc) && empty($params->bcc));
    }

    protected static function emailListToArray($emailList) {
        return (is_array($emailList)) ? $emailList : explode(',', $emailList);
    }

    public static function send($params = null) {
        //Faz uma tentativa de envio do email
        $mailer = self::getMailer($params);
        return $mailer->send();
    }

}

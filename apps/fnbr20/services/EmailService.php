<?php

use Maestro\Services\MMailer;
use Maestro\Services\Exception\ERuntimeException;

class EmailService extends MService {

    public function sendEmailThroughSystem($from, $recipients = '', $subject = '', $body = '') {
        $params = new \stdClass();
        $params->from = $from->from;
        $params->fromName = $from->fromName;
        $params->isHTML = true;
        $params->to = $recipients;
        $params->subject = $subject;
        $params->body = $body;
        $mailer = MMailer::getMailer($params);
        if (!$mailer->send()) {
            $msg = 'Message could not be sent.' . 'Mailer Error: ' . $mailer->ErrorInfo;
            throw new ERuntimeException($msg);
        }
    }

    public function sendSystemEmail($recipients = '', $subject = '', $body = '') {
        $params = new \stdClass();
        $params->isHTML = true;
        $params->to = $recipients;
        $params->subject = $subject;
        $params->body = $body;
        $mailer = MMailer::getMailer($params);
        if (!$mailer->send()) {
            $msg = 'Message could not be sent.' . 'Mailer Error: ' . $mailer->ErrorInfo;
            throw new ERuntimeException($msg);
        }
    }

}

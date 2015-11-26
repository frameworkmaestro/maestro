<?php

class SessionFilter extends MFilter
{

    public function preProcess()
    {
        // exemplo de alteração da configuração dependendo do controller sendo executado
        $controller = $this->handler->getName();
        if ($controller == 'actins') {
            Manager::setConf('session.check', false);
        }
        // é necessário validar a sessão?
        if (Manager::getConf('login.check') || Manager::getConf('session.check')) {
            $timeout = Manager::getSession()->checkTimeout(Manager::getConf('session.exception'));
        }
        if ($timeout) {
            $this->handler->canCallHandler(false);
            $url = Manager::getURL(Manager::getApp() . '/main');
            $this->handler->redirect($url);
        }
    }

}
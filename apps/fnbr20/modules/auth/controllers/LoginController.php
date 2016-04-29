<?php

Manager::import("auth\models\*");
manager::import("fnbr20\models\Base", "Base");

class LoginController extends \MController
{

    public function init()
    {
        Manager::checkLogin(false);
    }

    public function logout()
    {
        Manager::getAuth()->logout();
        $this->redirect(Manager::getURL('fnbr20/main'));
    }

    public function authenticate()
    {
        if ($this->data->datasource == '') {
            $this->data->datasource = 'fnapolo'; //$this->renderPrompt('error', 'Inform database name.');
        }
        Manager::setConf('fnbr20.db', $this->data->datasource);
        Manager::getSession()->fnbr20db = $this->data->datasource;
        $auth = Manager::getAuth();
        $this->data->result = $auth->authenticate($this->data->user, $this->data->challenge, $this->data->response);
        if ($this->data->result) {
            $user = Manager::getLogin()->getUser();
            $this->data->idLanguage = $user->getConfigData('fnbr20IdLanguage');
            if ($this->data->idLanguage == '') {
                $this->data->idLanguage = 1;
                $user->setConfigData('fnbr20IdLanguage', $this->data->idLanguage);
            }
            if ($this->data->ifLanguage == '') {
                $this->data->ifLanguage = 'en'; //$this->renderPrompt('error', 'Inform language.');
            }

            Manager::getSession()->idLanguage = $this->data->idLanguage;
            Manager::getSession()->lang = $this->data->ifLanguage;
            Manager::getSession()->fnbr20Level = $user->getUserLevel();

            $this->redirect(Manager::getURL('fnbr20/main'));
        } else {
            $this->renderPrompt('error', 'Login or password not valid.');
        }
    }

}

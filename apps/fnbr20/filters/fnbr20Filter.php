<?php

class FNBr20Filter extends MFilter {

    public function preProcess() {
        $data = Manager::getData();
        if (Manager::isLogged()) {
            $login = Manager::getLogin();
            $userIdLanguage = $login->getUser()->getConfigData('fnbr20IdLanguage');
        }        
        $idLanguage = $data->lang;
        if ($idLanguage == '') {
            $idLanguage = Manager::getSession()->idLanguage;
            if ($idLanguage == '') {
                $idLanguage = $userIdLanguage;
                if ($idLanguage == '') {
                    $idLanguage = 1;
                }
            }
        }
        Manager::getSession()->idLanguage = $idLanguage;
        $db = $data->datasource ? : (Manager::getSession()->fnbr20db ? : Manager::getConf('fnbr20.db'));
        Manager::getSession()->fnbr20db = $db;
        Manager::setConf('fnbr20.db', $db);
        Manager::setConf('options.language', Base::languages()[$idLanguage]);
    }

}


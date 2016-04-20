<?php

/* Copyright [2011, 2012, 2013] da Universidade Federal de Juiz de Fora
 * Este arquivo é parte do programa Framework Maestro.
 * O Framework Maestro é um software livre; você pode redistribuí-lo e/ou 
 * modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada 
 * pela Fundação do Software Livre (FSF); na versão 2 da Licença.
 * Este programa é distribuído na esperança que possa ser  útil, 
 * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL 
 * em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

namespace Maestro\Security;

use Maestro\Manager,
    Maestro\Utils\MUtil;

class MAuth {

    private $login;  // objeto MLogin
    public $idUser; // iduser do usuario corrente
    public $module; // authentication module;

    public function __construct() {
        $this->module = Manager::getConf('login.module');
    }
    
    public function isValidLogin() {
        return ($this->login instanceof MLogin) || ($this->login instanceof \MLogin);
    }

    public function setLogin($login = false) {
        Manager::getSession()->setValue('__sessionLogin', $login);
        $this->login = $login;
        $this->idUser = ($this->isValidLogin() ? $this->login->getIdUser() : NULL);
        $this->module = Manager::getConf('login.module');
    }

    public function setLoginLogUserId($userId) {
        $_SESSION['loginLogUserId'] = $userId;
    }

    public function getLoginLogUserId() {
        return $_SESSION['loginLogUserId'];
    }

    public function setLoginLog($login) {
        $_SESSION['loginLog'] = $login;
    }

    public function getLoginLog() {
        return $_SESSION['loginLog'];
    }

    public function getLogin() {
        return $this->login;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function checkLogin() {
        Manager::logMessage('[LOGIN] Running CheckLogin');

// if not checking logins, we are done
        if ((!MUtil::getBooleanValue(Manager::getConf('login.check')))) {
            Manager::logMessage('[LOGIN] I am not checking login today...');
            return true;
        }

// we have a session login?
        $session = Manager::getSession();
        $login = $session->getValue('__sessionLogin');
        if ($login) {
            if ($login->getLogin()) {
                Manager::logMessage('[LOGIN] Using session login: ' . $login->getLogin());
                $this->setLogin($login);
                return true;
            }
        }

// if we have already a login, assume it is valid and return
        if ($this->login instanceof MLogin) {
            Manager::logMessage('[LOGIN] Using existing login:' . $this->login->getLogin());
            return true;
        }

        Manager::logMessage('[LOGIN] No Login but Login required!');
        return false;
    }

    public function authenticate($user, $pass) {
        return false;
    }

    public function isLogged() {
        if ($this->isValidLogin()) {
            return ($this->login->getLogin() != NULL);
        } else {
            return false;
        }
    }

    public function logout($forced = '') {
        $this->setLogin(NULL);
        Manager::getSession()->destroy();
    }

}

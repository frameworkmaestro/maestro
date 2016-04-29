<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage 
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

manager::import("fnbr20\models\Base", "Base");
manager::import("fnbr20\models\Language", "Language");
Manager::import("auth\models\User","User");

class MainController extends \MController {

    public function init(){
        Manager::checkLogin(false);
    }

    public function main() {
        if (Manager::isLogged()) {
            $this->render('formMain');
        } else {
            $this->data->datasources = Manager::getConf('fnbr20.datasource');
            $this->render('formLogin');
        }
    }

    public function formMain() {
        $this->render();
    }

    public function changeLanguage() {
        //Manager::setConf('fnbr20.lang', $this->data->id);
        $idLanguage = Base::getIdLanguage($this->data->id);
        Manager::getSession()->idLanguage = $idLanguage;
        $this->redirect(Manager::getURL('fnbr20/main'));
    }

    public function changeLevel() {
        $login = Manager::getLogin(); 
        $toLevel = $this->data->id;
        $user = $login->getUser();
        $levels = $user->getAvaiableLevels();
        if ($levels[$toLevel]) {
            $newUser = new User($levels[$toLevel]);
            $login->setUser($newUser);
            Manager::getSession()->fnbr20Layers = $newUser->getConfigData('fnbr20Layers');
            Manager::getSession()->fnbr20Level = $toLevel;
            $this->redirect(Manager::getURL('fnbr20/main'));
        } else {
            $this->renderPrompt('error',_M('You don\'t have such level.'));
        }
        
    }
    
    public function jcryption(){
        $path = Manager::getAppPath('conf');
        $pathPUB = $path . '/rsa_1024_pub.pem';
        $pathPVT = $path . '/rsa_1024_priv.pem';
        $jc = new jcryption($pathPUB, $pathPVT);
        $jc->go();
        header('Content-type: text/plain');
        print_r($_POST);
        die();
    }
}

?>
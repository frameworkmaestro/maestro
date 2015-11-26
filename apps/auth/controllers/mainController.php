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

Manager::import("auth\models\*");

class MainController extends \MController {

    public function init(){
    }

    public function main() {
        $this->render();
    }

    public function formLogin() {
        $this->render();
    }

    public function logout() {
        Manager::getAuth()->logout();
        $this->redirect(Manager::getURL('dlivro/main'));
    }

    public function authenticate() {
        $auth = Manager::getAuth();
        $this->data->result = $auth->authenticate($this->data->user, $this->data->challenge, $this->data->response);
        if ($this->data->result) {
            mdump("++++++++++++");
            $this->redirect(Manager::getURL('dlivro/main'));
        } else {
            $this->renderPrompt('error','Login ou senha inválidos. Tente novamente.');
        }
    }


}

?>
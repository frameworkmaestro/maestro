<?php
/**
 * $_comment
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage $_package
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

Manager::import("auth\models\*");

class MessageController extends MController {

    private $idLanguage;
        
    public function init()
    {
        parent::init();
        $this->idLanguage = Manager::getConf('options.language');
        $msgDir = Manager::getAppPath('conf/report');
        Manager::$msg->file = 'messages.' . $this->idLanguage . '.php';
        Manager::$msg->addMessages($msgDir);
    }
    
    public function main() {
        $this->render("formBase");
    }

    public function formMail(){
        $user = new User();
        $this->data->users = $user->listByFilter()->asQuery()->chunkResult('idUser','name');
        $group = new Group();
        $this->data->groups = $group->listByFilter()->asQuery()->chunkResult('idGroup','name');
        $this->data->send = "@fnbr20/auth/message/mail|formMail";
        $this->render();
    }
    
    public function mail() {
        try {
            $emailService = MApp::getService('fnbr20', '', 'email');
            $to = [];
            if ($this->data->toUser != '') {
                $user = new User($this->data->toUser);
                $email = $user->getPerson()->getEmail(); 
                $to[$email] = $email;
            }
            if ($this->data->toGroup != '') {
                $group = new Group($this->data->toGroup);
                $users = $group->getUsers();
                foreach ($users as $user) {
                    $email = $user->getPerson()->getEmail(); 
                    $to[$email] = $email;
                }
            }
            $fromUser = Base::getCurrentUser();
            $from = (object)[
                'from' => $fromUser->getPerson()->getEmail(),
                'fromName' => $fromUser->getPerson()->getName(),
            ];
            $emailService->sendEmailThroughSystem($from, $to, $this->data->subject, $this->data->body);
            $this->renderPrompt('information', 'Ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }    
}
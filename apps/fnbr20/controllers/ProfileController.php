<?php

 use Maestro\Services\Exception\ESecurityException;

Manager::import("fnbr20\models\*");
Manager::import("auth\models\*");

class ProfileController extends MController {

    public function main() {
        $this->renderPrompt("info", "Em desenvolvimento");
    }
    
    public function formMyProfile() {
        $user = Base::getCurrentUser();
        $this->data->idUser = $user->getId();
        $this->data->languagePreference = $user->getConfigData('fnbr20LangPref');
        $this->data->languages = Base::languages();
        $this->data->title = "Profile of " . $user->getLogin();
        $this->render();
    }

    public function formChangePassword() {
        $user = Base::getCurrentUser();
        $this->data->idUser = $user->getId();
        $this->data->title = "Change Password of " . $user->getLogin();
        $this->render();
    }

    public function myProfile()
    {
        try {
            $user = new User($this->data->idUser);
            $user->setConfigData('fnbr20LangPref', $this->data->languagePreference);
            $this->renderPrompt('information', 'Ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }

    }
    
    public function changePassword() {
        try {
            jcryption::decrypt();
            $this->setData($_POST);
            $user = new User($this->data->idUser);
            if (!$user->validatePassword($this->data->current)) {
                throw new ESecurityException('Wrong password!');
            }
            if ($this->data->newPassword != $this->data->newPassword) {
                throw new ESecurityException('Passwords dont matches!');
            }
            $user->newPassword($this->data->newPassword);
            $go = "!$('#formChangePassword_dialog').dialog('close');";        
            $this->renderPrompt('information', 'Password changed!', $go);
        } catch (\Exception $e) {
            $go = "!$('#formChangePassword_dialog').dialog('close');";        
            $this->renderPrompt('error', $e->getMessage(), $go);
        }
    }
    
}
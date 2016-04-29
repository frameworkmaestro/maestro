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
Manager::import("fnbr20\models\Base");
Manager::import("fnbr20\models\LU");
Manager::import("auth\models\*");

class UserController extends MController {

    public function main() {
        $this->data->query = Manager::getAppURL('fnbr20', 'auth/user/gridData');
        $this->render();
    }

    public function gridData() {
        $model = new User();
        $criteria = $model->listForGrid($this->data->filter);
        $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formObject() {
        $model = new User($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->object->userLevel = $model->getUserLevel();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("New User");
        $this->data->userLevel = Base::userLevel();
        $this->data->save = "@fnbr20/auth/user/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@fnbr20/auth/user/delete/" . $model->getId() . '|formObject';
        $this->render();
    }

    public function formConstraintsLU() {
        $model = new User($this->data->id);
        $this->data->title = $model->getLogin() . ' :: Constraints_LU';
        $this->data->save = "@fnbr20/auth/user/saveConstraintsLU/" . $model->getId() . '|formConstraintsLU';
        $this->render();
    }

    public function formPreferences() {
        $user = new User($this->data->id);
        $this->data->title = $user->getLogin() . ' :: Preferences';
        $this->data->save = "@fnbr20/auth/user/savePreferences|formPreferences";
        $userLevel = $user->getUserLevel();
        if ($userLevel == 'BEGINNER') {
            $this->data->isBeginner = true;
            $this->data->idJunior = $user->getConfigData('fnbr20JuniorUser');
            $this->data->junior = $user->getUsersOfLevel('JUNIOR');
            mdump($this->data);
        }
        if ($userLevel == 'JUNIOR') {
            $this->data->isJunior = true;
            $this->data->idSenior = $user->getConfigData('fnbr20SeniorUser');
            $this->data->senior = $user->getUsersOfLevel('SENIOR');
            mdump($this->data);
        }
        if ($userLevel == 'SENIOR') {
            $this->data->isSenior = true;
            $this->data->idMaster = $user->getConfigData('fnbr20MasterUser');
            $this->data->master = $user->getUsersOfLevel('MASTER');
            mdump($this->data);
        }
        $this->data->userLevel = $userLevel;
        $this->render();
    }

    public function formResetPassword() {
        $yes = ">fnbr20/auth/user/resetPassword/" . $this->data->id;
        $this->renderPrompt('question', _M("Confirm password reset?"), $yes, "");
    }

    public function save() {
        try {
            $model = new User($this->data->id);
            $model->setData($this->data->user);
            $model->save();
            $model->setUserLevel($this->data->user->userLevel);
            $this->renderPrompt('information', 'OK', "jQuery('#gf_LU').datagrid('reload');");
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete() {
        try {
            $model = new User($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function getConstraintsLU() {
        $idUser = $this->data->id;
        $user = new User($idUser);
        $lus = $user->getConfigData('fnbr20ConstraintsLU');
        $lu = new LU();
        if (is_array($lus) && count($lus)) {
            $result = $lu->listForConstraint($lus)->asQuery()->getResult();
            foreach ($result as $row) {
                $l[] = (object) $row;
            }
            $r = $l;
        } else {
            $r = null;
        }
        $this->renderJson(json_encode($r));
    }

    public function saveConstraintsLU() {
        try {
            $user = new User($this->data->user->idUser);
            $lus = $user->getConfigData('fnbr20ConstraintsLU');
            foreach ($this->data->gridfieldlu->listLU as $lu) {
                $lus[] = $lu->idLU;
            }
            $user->setConfigData('fnbr20ConstraintsLU', $lus);
            // assign same LU to supervisor
            $userLevel = $user->getUserLevel();
            if ($userLevel == 'BEGINNER') {
                $idSupervisor = $user->getConfigData('fnbr20JuniorUser');
                if ($idSupervisor != '') {
                    $supervisor = new User($idSupervisor);
                    $lus = $supervisor->getConfigData('fnbr20ConstraintsLU');
                    foreach ($this->data->gridfieldlu->listLU as $lu) {
                        $lus[] = $lu->idLU;
                    }
                    $supervisor->setConfigData('fnbr20ConstraintsLU', $lus);
                }    
            }
            $this->renderPrompt('information', 'Ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function savePreferences() {
        try {
            $user = new User($this->data->user->idUser);
            $userLevel = $this->data->user->level;
            if ($userLevel == 'BEGINNER') {
                $user->setConfigData('fnbr20JuniorUser', $this->data->idJunior);
            } else if ($userLevel == 'JUNIOR') {
                $user->setConfigData('fnbr20SeniorUser', $this->data->idSenior);
            } else if ($userLevel == 'SENIOR') {
                $user->setConfigData('fnbr20MasterUser', $this->data->idMaster);
            }
            $this->renderPrompt('information', 'Ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function resetPassword() {
        try {
            $user = new User($this->data->id);
            $user->resetPassword();
            $this->renderPrompt('information', 'Ok');
        } catch (\Exception $e) {
            $this->renderPrompt('error', $e->getMessage());
        }
    }

}

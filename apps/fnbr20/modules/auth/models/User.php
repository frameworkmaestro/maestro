<?php

/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace auth\models;

\Maestro\Manager::import("fnbr20\models\Base");

class User extends map\UserMap {

    public static function config() {
        return array(
            'log' => array(),
            'validators' => array(
                'login' => array('notnull'),
                'pwd' => array('notnull'),
                'passMD5' => array('notnull'),
                'theme' => array('notnull'),
                'active' => array('notnull'),
                'idPerson' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription() {
        return $this->getLogin();
    }

    public function delete() {
        $this->deleteAssociation('groups');
        parent::delete();
    }

    public function listByFilter($filter) {
        $criteria = $this->getCriteria()->select('*, person.name')->orderBy('login');
        if ($filter->idUser) {
            $criteria->where("idUser = {$filter->idUser}");
        }
        if ($filter->login) {
            $criteria->where("login LIKE '{$filter->login}%'");
        }
        if ($filter->name) {
            $criteria->where("person.name LIKE '{$filter->name}%'");
        }
        return $criteria;
    }

    public function listForGrid($filter) {
        $levels = array_keys(\Base::userLevel());
        $constraintsLU = _M("Constraints_LU");
        $preferences = _M("Preferences");
        $criteria = $this->getCriteria()->select("*, idUser as resetPassword, person.name, person.email, groups.name as level, " .
                        "IF((groups.name = 'BEGINNER') or (groups.name = 'JUNIOR') or (groups.name = 'SENIOR'), '{$constraintsLU}','') as constraints, '{$preferences}' as preferences")->orderBy('login');
        if ($filter->idUser) {
            $criteria->where("idUser = {$filter->idUser}");
        }
        if ($filter->login) {
            $criteria->where("login LIKE '{$filter->login}%'");
        }
        if ($filter->name) {
            $criteria->where("person.name LIKE '{$filter->name}%'");
        }
        if ($filter->level) {
            $criteria->where("upper(groups.name) LIKE upper('{$filter->level}%')");
        }
        $criteria->where('upper(groups.name)', 'IN', $levels);
        return $criteria;
    }

    public function getName() {
        return $this->login;
    }

    public function getForRegisterLogin() {
        
    }

    public function getArrayGroups() {
        $aGroups = array();
        $groups = $this->getGroups();
        foreach ($groups as $group) {
            $g = $group->getName();
            $aGroups[$g] = $g;
        }
        return $aGroups;
    }

    public function getRights() {
        $query = $this->getCriteria()->
                select('groups.access.transaction.name', 'max(groups.access.rights) as rights')->
                where("login = '{$this->login}'")->
                groupBy('groups.access.transaction.name')->
                asQuery();
        return $query->chunkResult('name', 'rights', false);
    }

    public function weakPassword() {
        $weak = ($this->passMD5 == MD5('010101')) || ($this->passMD5 == MD5($this->login));
        return $weak;
    }

    public function resetPassword() {
        $this->newPassword(\Manager::getOptions('defaultPassword'));
    }

    public function newPassword($password) {
        $this->setPassMD5(md5($password));
        $this->save();
    }

    public function validatePassword($password) {
        return ($this->getPassMD5() == md5($password));
    }

    public function validatePasswordMD5($challenge, $response) {
        $hash_pass = MD5(trim($this->login) . ':' . trim($this->passMD5) . ":" . $challenge);
        return ($hash_pass == $response);
    }

    public function getByLogin($login) {
        $criteria = $this->getCriteria()->
                where("login = '{$login}'");
        $this->retrieveFromCriteria($criteria);
        return $this;
    }

    public function listGroups() {
        $criteria = $this->getCriteria()->select("groups.idGroup,groups.name")->orderBy("groups.name");
        if ($this->idUser) {
            $criteria->where("idUser = {$this->idUser}");
        }
        return $criteria;
    }

    public function getConfigData($attr) {
        $config = parent::getConfig();
        if ($config == '') {
            $config = new \StdClass();
            $config->$attr = '';
        } else {
            $config = unserialize($config);
        }
        return $config->$attr;
    }

    public function setConfigData($attr, $value) {
        $config = parent::getConfig();
        if ($config == '') {
            $config = new \StdClass();
            $config->$attr = '';
        } else {
            $config = unserialize($config);
        }
        $config->$attr = $value;
        parent::setConfig(serialize($config));
        parent::save();
    }

    public function getUserLevel() {
        $userLevel = '';
        $levels = \Base::userLevel();
        $groups = $this->getArrayGroups();
        foreach ($levels as $level => $levelName) {
            if ($groups[$level]) {
                $userLevel = $level;
            }
        }
        return $userLevel;
    }

    public function getAvaiableLevels() {
        $levels = [];
        $criteria = $this->getCriteria()->
                select('idUser')->
                where("idPerson = {$this->getIdPerson()}");
        $users = $criteria->asQuery()->getResult();
        foreach ($users as $row) {
            $idUser = $row['idUser'];
            $tempUser = new User($idUser);
            $level = $tempUser->getUserLevel();
            $levels[$level] = $idUser;
        }
        return $levels;
    }

    public function setUserLevel($userLevel) {
        $levels = \Base::userLevel();
        $currentLevel = $this->getUserLevel();
        if ($currentLevel != $userLevel) {
            $newGroups = [];
            foreach ($this->groups as $group) {
                if (!(in_array($group->getName(), $levels))) {
                    $newGroups[] = $group;
                }
            }
            $g = new Group();
            $g->getByName($userLevel);
            $newGroups[] = $g;
            $this->groups = $newGroups;
            $this->saveAssociation('groups');
        }
    }

    public function getUsersOfLevel($level) {
        $criteria = $this->getCriteria()->select("idUser, login")
                ->where("groups.name = '{$level}'")
                ->orderBy("login");
        return $criteria->asQuery()->chunkResult('idUser', 'login');
    }

    public function getUserSupervisedByIdLU($idLU) {
        $criteria = $this->getCriteria()->select('idUser,config');
        $rows = $criteria->asQuery()->getResult();
        foreach ($rows as $row) {
            $config = unserialize($row['config']);
            $lus = $config->fnbr20ConstraintsLU;
            if ($lus) {
                foreach ($lus as $id) {
                    if ($idLU == $id) {
                        $userSupervised = new User($row['idUser']);
                        return $userSupervised;
                    }
                }
            }
        }
        return NULL;
    }

    public function save() {
        if ($this->getPassMD5() == '') {
            $this->setPassMD5(md5(\Manager::getOptions('defaultPassword')));
        }
        if ($this->getTheme() == '') {
            $this->setTheme('default');
        }
        parent::save();
    }

}

?>
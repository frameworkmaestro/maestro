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

use Maestro\Manager;


class MLogin {

    /**
     * Attribute Description.
     */
    private $login; // login at common::user

    /**
     * Attribute Description.
     */
    public $time; // login time
    /**
     * Attribute Description.
     */
    private $name; // full user name
    /**
     * Attribute Description.
     */
    private $userData; // an array of data chunks associated to module
    /**
     * Attribute Description.
     */
    private $idUser; // iduser at common::user
    /**
     * Attribute Description.
     */
    private $profile; // profile at common::user
    /**
     * Attribute Description.
     */
    private $isAdmin;
    /**
     * Attribute Description.
     */
    private $idSession;
    /**
     * Attribute Description.
     */
    private $rights;
    /**
     * Attribute Description.
     */
    private $groups;
    /**
     * Attribute Description.
     */
    private $idPerson;  // idPerson at common::user
    /**
     * Attribute Description.
     */
    private $lastAccess;
    private $weakPass;

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $id (tipo) desc
     * @param $password (tipo) desc
     * @param $user (tipo) desc
     * @param $idkey (tipo) desc
     * @param $setor' (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function __construct($user = '', $name = '', $idUser = '') {
        if ($user instanceof \Maestro\Persistence\PersistentObject) { // it can be a User object
            $this->setUser($user);
        } else { // $user is the login string
            $this->login = $user;
            $this->name = $name;
            $this->idUser = $idUser;
            $this->isAdmin = false;
        }
        $this->time = time();
    }

    public function setUser(\Maestro\Persistence\PersistentObject $user) {
        $user->getForRegisterLogin();
        $this->login = $user->getLogin();
        $this->name = $user->getName();
        $this->idUser = $user->getId();
        $this->setGroups($user->getArrayGroups());
        $this->setRights($user->getRights());
        $this->weakPass = $user->weakPassword();
        $this->weakPass = false;
    }
    
    public function getUser() {
        if ($this->idUser) {
            $user = Manager::getModelMAD('user');
            $user->getById($this->idUser);
            return $user;
        }
        return NULL;
    }

    public function getLogin(){
        return $this->login;
    }

    public function getIdUser(){
        return $this->idUser;
    }

    public function getName(){
        return $this->name;
    }

    public function getTime(){
        return $this->time;
    }

    public function getUserData($module) {
        return $this->userData[$module];
    }

    public function setUserData($module, $data) {
        $this->userData[$module] = $data;
    }

    public function setRights($rights) {
        $this->rights = $rights;
    }

    public function getRights($transaction = '') {
        if ($transaction){
            return $this->rights[$transaction];
        }
        return $this->rights;
    }

    public function setGroups($groups) {
        $this->groups = $groups;
        $this->isAdmin(array_key_exists('ADMIN', $groups));
    }

    public function getGroups() {
        return $this->groups;
    }

    public function isAdmin($isAdmin = NULL) {
        if ($isAdmin !== NULL) {
            $this->isAdmin = $isAdmin;
        }
        return $this->isAdmin;
    }

    public function isMemberOf($group){
        return Manager::getPerms()->isMemberOf($group);
    }

    public function isWeakPassword(){
        return $this->weakPass;
    }

    public function setIdPerson($idPerson) {
        $this->idPerson = $idPerson;
    }

    public function setLastAccess($data) {
        $this->lastAccess->tsIn = $data->tsIn;
        $this->lastAccess->tsOut = $data->tsOut;
        $this->lastAccess->remoteAddr = $data->remoteAddr;
    }

    public function isModuleAdmin($module) {
        $group = 'ADMIN' . strtoupper($module);
        return array_key_exists($group, $this->groups);
    }

}

?>
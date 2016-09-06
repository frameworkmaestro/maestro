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

use Maestro\Manager;


class MLogin {

    /**
     * Attribute Description.
     */
    protected $login; // login at common::user

    /**
     * Attribute Description.
     */
    public $time; // login time
    /**
     * Attribute Description.
     */
    protected $data;
    /**
     * Attribute Description.
     */
    //protected $name; // full user name
    /**
     * Attribute Description.
     */
    //protected $userData; // an array of data chunks associated to module
    /**
     * Attribute Description.
     */
    //protected $idUser; // iduser at common::user
    /**
     * Attribute Description.
     */
    //protected $profile; // profile at common::user
    /**
     * Attribute Description.
     */
    //protected $isAdmin;
    /**
     * Attribute Description.
     */
    //protected $idSession;
    /**
     * Attribute Description.
     */
    //protected $rights;
    /**
     * Attribute Description.
     */
    //protected $groups;
    /**
     * Attribute Description.
     */
    //protected $idPerson;  // idPerson at common::user
    /**
     * Attribute Description.
     */
    //protected $lastAccess;
    //protected $weakPass;

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
        $this->data = new \stdClass();
        if ($user instanceof \Maestro\Persistence\PersistentObject) { // it can be a User object
            $this->setUser($user);
        } else { // $user is the login string
            $this->login = $this->data->login = $user;
            $this->data->name = $name;
            $this->data->idUser = $idUser;
            $this->data->isAdmin = false;
        }
        $this->time = $this->data->time = time();
    }

    public function setData($data) {
        $this->data = $data;
        $this->login = $data->login;
        $this->time = $data->time;
    }

    public function getData() {
        return $this->data;
    }

    public function setUser(\Maestro\Persistence\PersistentObject $user) {
        $this->login = $this->data->login = $user->getLogin();
        $this->data->name = $user->getName();
        $this->data->idUser = $user->getId();
        $this->setGroups($user->getArrayGroups());
        $this->setRights($user->getRights());
        $this->data->weakPass = $user->weakPassword();
    }
    
    public function getUser() {
        if ($this->data->idUser) {
            $user = Manager::getModelMAD('user');
            $user->getById($this->data->idUser);
            return $user;
        }
        return NULL;
    }

    public function getLogin(){
        return $this->login;
    }

    public function getIdUser(){
        return $this->data->idUser;
    }

    public function getName(){
        return $this->data->name;
    }

    public function getTime(){
        return $this->time;
    }

    public function getUserData($module) {
        return $this->data->userData[$module];
    }

    public function setUserData($module, $data) {
        $this->data->userData[$module] = $data;
    }

    public function setRights($rights) {
        $this->data->rights = $rights;
    }

    public function getRights($transaction = '') {
        if ($transaction){
            return $this->data->rights[$transaction];
        }
        return $this->data->rights;
    }

    public function setGroups($groups) {
        $this->data->groups = $groups;
        $this->isAdmin(array_key_exists('ADMIN', $groups));
    }

    public function getGroups() {
        return $this->data->groups;
    }

    public function isAdmin($isAdmin = NULL) {
        if ($isAdmin !== NULL) {
            $this->data->isAdmin = $isAdmin;
        }
        return $this->data->isAdmin;
    }

    public function isMemberOf($group){
        return Manager::getPerms()->isMemberOf($group);
    }

    public function isWeakPassword(){
        return $this->data->weakPass;
    }

    public function setIdPerson($idPerson) {
        $this->data->idPerson = $idPerson;
    }

    public function setLastAccess($data) {
        $this->data->lastAccess = (object) [
            'tsIn' => $data->tsIn,
            'tsOut' => $data->tsOut,
            'remoteAddr' => $data->remoteAddr
        ];
    }

    public function isModuleAdmin($module) {
        $group = 'ADMIN' . strtoupper($module);
        return array_key_exists($group, $this->groups);
    }

}

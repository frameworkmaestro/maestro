<?php
/**
 * Created by PhpStorm.
 * User: elymatos
 * Date: 4/21/2016
 * Time: 5:27 PM
 */

namespace ddd\models;


class User
{
    /**
     *
     * @var string
     */
    protected $login;
    /**
     *
     * @var string
     */
    protected $password;
    /**
     *
     * @var string
     */
    protected $passMD5;

    /**
     * Associations
     */
    protected $person;
    protected $sector;
    protected $groups;

    public function getIdSector() {
        return $this->idSector;
    }

    public function setIdSector($value) {
        $this->idSector = $value;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($value) {
        $this->login = $value;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($value) {
        $this->password = $value;
    }

    public function getPassMD5() {
        return $this->passMD5;
    }

    public function setPassMD5($value) {
        $this->passMD5 = $value;
    }
    /**
     *
     * @return Association
     */
    public function getPerson() {
        return  $this->person;
    }
    /**
     *
     * @param Association $value
     */
    public function setPerson($value) {
        $this->person = $value;
    }
    /**
     *
     * @return Association
     */
    public function getSector() {
        return  $this->sector;
    }
    /**
     *
     * @param Association $value
     */
    public function setSector($value) {
        $this->sector = $value;
    }
    /**
     *
     * @return Association
     */
    public function getGroups() {
        return  $this->groups;
    }
    /**
     *
     * @param Association $value
     */
    public function setGroups($value) {
        $this->groups = $value;
    }

}
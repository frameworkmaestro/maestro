<?php

namespace ddd\persistence\maestro\proxy;

use ddd\models\map\UserMap as UserMap;

class UserProxy extends \ddd\models\User {

    private $map;

    public function __construct($data, UserMap $map = null)
    {
        $this->map = $map ?: new UserMap($data, $this);
    }

    /**
     * 
     * @var integer 
     */
    protected $idUser;
    /**
     * 
     * @var integer 
     */
    protected $idPerson;
    /**
     * 
     * @var integer 
     */
    protected $idSector;


    /**
     * Getters/Setters
     */
    public function getIdUser() {
        return $this->idUser;
    }

    public function setIdUser($value) {
        $this->idUser = ($value ? : NULL);
    }

    public function getIdPerson() {
        return $this->idPerson;
    }

    public function setIdPerson($value) {
        $this->idPerson = $value;
    }

    public function getIdSector() {
        return $this->idSector;
    }

    public function setIdSector($value) {
        $this->idSetor = $value;
    }
/*
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
*/
    /**
     *
     * @return Association
     */
    public function getPerson() {
        if (is_null($this->person)){
            $this->map->retrieveAssociation("person");
        }
        return  $this->person;
    }
    /**
     *
     * @param Association $value
     */
    public function setPerson($value) {
        $this->person = $value->getModel();
    }
    /**
     *
     * @return Association
     */
    public function getAssociationPerson() {
        $this->map->retrieveAssociation("person");
    }
    /**
     *
     * @return Association
     */
    public function getSector() {
        if (is_null($this->sector)){
            $this->map->retrieveAssociation("sector");
        }
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
    public function getAssociationSector() {
        $this->map->retrieveAssociation("sector");
    }
    /**
     *
     * @return Association
     */
    public function getGroups() {
        if (is_null($this->groups)){
            $this->map->retrieveAssociation("groups");
        }
        return  $this->groups;
    }
    /**
     *
     * @param Association $value
     */
    public function setGroups($value) {
        $this->groups = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationGroups() {
        $this->map->retrieveAssociation("groups");
    }

}

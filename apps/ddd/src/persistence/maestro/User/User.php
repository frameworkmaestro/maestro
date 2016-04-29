<?php

namespace ddd\persistence\maestro\User;

class User extends \ddd\models\User {

    private $map;

    public function getMap()
    {
        if ($this->map == null) {
            $this->map = new UserMap($this);
        }
        return $this->map;
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
    /**
     *
     * @return Association
     */
    public function getPerson() {
        if (is_null($this->person)){
            $this->getMap()->retrieveAssociation("person");
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
        $this->getMap()->retrieveAssociation("person");
    }
    /**
     *
     * @return Association
     */
    public function getSector() {
        if (is_null($this->sector)){
            $this->getMap()->retrieveAssociation("sector");
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
        $this->getMap()->retrieveAssociation("sector");
    }
    /**
     *
     * @return Association
     */
    public function getGroups() {
        if (is_null($this->groups)){
            $this->getMap()->retrieveAssociation("groups");
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
        $this->getMap()->retrieveAssociation("groups");
    }

}

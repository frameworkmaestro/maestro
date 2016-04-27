<?php

namespace ddd\persistence\maestro\Person;


use ddd\persistence\maestro\Person\PersonMap as PersonMap;

class PersonProxy extends \ddd\models\Person {

    private $map;

    public function __construct($data, PersonMap $map = null)
    {
        $this->map = $map ?: new PersonMap($data, $this);
    }

    public function getMap()
    {
        return $this->map;
    }

    /**
     *
     * @var integer
     */
    protected $idPerson;

    /**
     * Getters/Setters
     */
    public function getIdPerson()
    {
        return $this->idPerson;
    }

    public function setIdPerson($value)
    {
        $this->idPerson = $value;
    }

}

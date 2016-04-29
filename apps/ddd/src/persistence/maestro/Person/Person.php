<?php

namespace ddd\persistence\maestro\Person;

class Person extends \ddd\models\Person {

    private $map;

    public function getMap()
    {
        if ($this->map == null) {
            $this->map = new PersonMap($this);
        }
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

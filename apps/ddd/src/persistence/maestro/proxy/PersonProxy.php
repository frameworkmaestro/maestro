<?php

namespace ddd\persistence\maestro\proxy;

use ddd\models\map\PersonMap as PersonMap;

class PersonProxy extends \ddd\models\Person {

    private $map;

    public function __construct($data, PersonMap $map = null)
    {
        $this->map = $map ?: new PersonMap($data, $this);
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

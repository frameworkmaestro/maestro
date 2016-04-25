<?php

namespace ddd\models\map;

class PersonMap extends \Maestro\MVC\MBusinessModel
{

    public function __construct($data = '')
    {
        parent::__construct($data, new \ddd\models\Person());
    }

    public static function ORMMap()
    {

        return array(
            'class' => \get_called_class(),
            'database' => 'ddd',
            'table' => 'Pessoa',
            'attributes' => array(
                'idPerson' => array('column' => 'idPessoa', 'key' => 'primary', 'idgenerator' => 'seq_pessoa', 'type' => 'integer'),
                'name' => array('column' => 'nome', 'type' => 'string'),
                'cpf' => array('column' => 'cpf', 'type' => 'cpf'),
                'birthDate' => array('column' => 'dataNascimento', 'type' => 'date'),
                'photo' => array('column' => 'foto', 'type' => 'blob'),
                'email' => array('column' => 'email', 'type' => 'string'),
            ),
            'associations' => array()
        );
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

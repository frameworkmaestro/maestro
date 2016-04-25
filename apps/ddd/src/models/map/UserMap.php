<?php

namespace ddd\models\map;

class UserMap extends \MBusinessModel {

    public function __construct($data = '')
    {
        parent::__construct($data, new \ddd\models\User());
    }

    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => 'ddd',
            'table' => 'Usuario',
            'attributes' => array(
                'idUser' => array('column' => 'idUsuario','key' => 'primary','idgenerator' => 'seq_usuario','type' => 'integer'),
                'idPerson' => array('column' => 'idPessoa','key' => 'foreign','type' => 'integer'),
                'idSector' => array('column' => 'idSetor','key' => 'foreign','type' => 'integer'),
                'login' => array('column' => 'login','type' => 'string'),
                'password' => array('column' => 'password','type' => 'string'),
                'passMD5' => array('column' => 'passMD5','type' => 'string'),
            ),
            'associations' => array(
                'person' => array('toClass' => '\exemplos\models\person', 'cardinality' => 'oneToOne' , 'keys' => 'idPerson:idPerson'),
                'sector' => array('toClass' => '\exemplos\models\sector', 'cardinality' => 'oneToOne' , 'keys' => 'idSector:idSector'),
                'groups' => array('toClass' => '\exemplos\models\group', 'cardinality' => 'manyToMany' , 'associative' => 'usuario_grupo'),
            )
        );
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
        if (is_null($this->person)){
            $this->retrieveAssociation("person");
        }
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
    public function getAssociationPerson() {
        $this->retrieveAssociation("person");
    }
    /**
     *
     * @return Association
     */
    public function getSector() {
        if (is_null($this->sector)){
            $this->retrieveAssociation("sector");
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
        $this->retrieveAssociation("sector");
    }
    /**
     *
     * @return Association
     */
    public function getGroups() {
        if (is_null($this->groups)){
            $this->retrieveAssociation("groups");
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
        $this->retrieveAssociation("groups");
    }

}

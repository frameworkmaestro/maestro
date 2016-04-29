<?php
/**
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2013 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

// wizard - code section created by Wizard Module

namespace auth\models\map;

class PersonMap extends \MBusinessModel {

    
    public static function ORMMap() {

        return array(
            'class' => \get_called_class(),
            'database' => \Manager::getConf('fnbr20.db'),
            'table' => 'auth_person',
            'attributes' => array(
                'idPerson' => array('column' => 'idPerson','key' => 'primary','idgenerator' => 'identity','type' => 'integer'),
                'name' => array('column' => 'name','type' => 'string'),
                'email' => array('column' => 'email','type' => 'string'),
                'nick' => array('column' => 'nick','type' => 'string'),
            ),
            'associations' => array(
                'users' => array('toClass' => 'auth\models\User', 'cardinality' => 'oneToMany' , 'keys' => 'idPerson:idPerson'), 
            )
        );
    }
    
    /**
     * 
     * @var integer 
     */
    protected $idPerson;
    /**
     * 
     * @var string 
     */
    protected $name;
    /**
     * 
     * @var string 
     */
    protected $email;
    /**
     * 
     * @var string 
     */
    protected $nick;

    /**
     * Associations
     */
    protected $users;
    

    /**
     * Getters/Setters
     */
    public function getIdPerson() {
        return $this->idPerson;
    }

    public function setIdPerson($value) {
        $this->idPerson = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($value) {
        $this->email = $value;
    }

    public function getNick() {
        return $this->nick;
    }

    public function setNick($value) {
        $this->nick = $value;
    }
    /**
     *
     * @return Association
     */
    public function getUsers() {
        if (is_null($this->users)){
            $this->retrieveAssociation("users");
        }
        return  $this->users;
    }
    /**
     *
     * @param Association $value
     */
    public function setUsers($value) {
        $this->users = $value;
    }
    /**
     *
     * @return Association
     */
    public function getAssociationUsers() {
        $this->retrieveAssociation("users");
    }

    

}
// end - wizard

?>
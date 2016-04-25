<?php
/**
 * Created by PhpStorm.
 * User: elymatos
 * Date: 4/21/2016
 * Time: 5:27 PM
 */

namespace ddd\models;


class Person
{
    private $name;
    private $cpf;
    private $birthDate;
    private $photo;
    private $email;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        if (!($cpf instanceof \Maestro\Types\MCPF)) {
            $cpf = new \Maestro\Types\MCPF($cpf);
        }
        $this->cpf = $cpf;

    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        if (!($birthDate instanceof \Maestro\Types\MDate)) {
            $birthDate = new \Maestro\Types\MDate($birthDate);
        }
        $this->birthDate = $birthDate;

    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


}
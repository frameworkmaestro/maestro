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
    private $login;

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }


}
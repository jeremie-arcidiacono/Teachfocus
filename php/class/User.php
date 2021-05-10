<?php

class User
{
    public $idUser;
    public $lastName;
    public $firstName;
    public $nickname;
    public $mail;
    public $userType;

    function __construct($idUser, $pLastName, $pFirstName, $pNickname, $pMail, $pUserType)
    {
        $this->idUser = $idUser;
        $this->lastName = $pLastName;
        $this->firstName = $pFirstName;
        $this->nickname = $pNickname;
        $this->mail = $pMail;
        $this->userType = $pUserType;
    }

    function __destruct()
    {
    }

    // Faut-il utilisé les fonctions magiques ou ne pas utiliser de getter/setter ?
    /*
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }*/
}

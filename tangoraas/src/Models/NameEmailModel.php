<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Name and Email Model
 */
class NameEmailModel implements JsonSerializable
{
    /**
     * Email Address
     * @required
     * @var string $email public property
     */
    public $email;

    /**
     * First Name
     * @required
     * @var string $firstName public property
     */
    public $firstName;

    /**
     * Last Name
     * @var string|null $lastName public property
     */
    public $lastName;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $email     Initialization value for $this->email
     * @param string $firstName Initialization value for $this->firstName
     * @param string $lastName  Initialization value for $this->lastName
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->email     = func_get_arg(0);
            $this->firstName = func_get_arg(1);
            $this->lastName  = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['email']     = $this->email;
        $json['firstName'] = $this->firstName;
        $json['lastName']  = $this->lastName;

        return $json;
    }
}

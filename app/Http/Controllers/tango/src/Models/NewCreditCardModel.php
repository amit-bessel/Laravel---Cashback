<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * New Credit Card
 */
class NewCreditCardModel implements JsonSerializable
{
    /**
     * card number
     * @required
     * @var string $number public property
     */
    public $number;

    /**
     * card expiration date in YYYY-MM format
     * @required
     * @var string $expiration public property
     */
    public $expiration;

    /**
     * 3 or 4 digit card security code on the back of card
     * @required
     * @var string $verificationNumber public property
     */
    public $verificationNumber;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $number             Initialization value for $this->number
     * @param string $expiration         Initialization value for $this->expiration
     * @param string $verificationNumber Initialization value for $this->verificationNumber
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->number             = func_get_arg(0);
            $this->expiration         = func_get_arg(1);
            $this->verificationNumber = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['number']             = $this->number;
        $json['expiration']         = $this->expiration;
        $json['verificationNumber'] = $this->verificationNumber;

        return $json;
    }
}

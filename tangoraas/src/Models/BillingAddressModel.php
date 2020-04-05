<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Billing Address
 */
class BillingAddressModel implements JsonSerializable
{
    /**
     * @todo Write general description for this property
     * @required
     * @var string $firstName public property
     */
    public $firstName;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $lastName public property
     */
    public $lastName;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $addressLine1 public property
     */
    public $addressLine1;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $city public property
     */
    public $city;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $state public property
     */
    public $state;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $postalCode public property
     */
    public $postalCode;

    /**
     * 2-letter country code
     * @required
     * @var string $country public property
     */
    public $country;

    /**
     * billing email address
     * @required
     * @var string $emailAddress public property
     */
    public $emailAddress;

    /**
     * @todo Write general description for this property
     * @var string|null $addressLine2 public property
     */
    public $addressLine2;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $firstName    Initialization value for $this->firstName
     * @param string $lastName     Initialization value for $this->lastName
     * @param string $addressLine1 Initialization value for $this->addressLine1
     * @param string $city         Initialization value for $this->city
     * @param string $state        Initialization value for $this->state
     * @param string $postalCode   Initialization value for $this->postalCode
     * @param string $country      Initialization value for $this->country
     * @param string $emailAddress Initialization value for $this->emailAddress
     * @param string $addressLine2 Initialization value for $this->addressLine2
     */
    public function __construct()
    {
        if (9 == func_num_args()) {
            $this->firstName    = func_get_arg(0);
            $this->lastName     = func_get_arg(1);
            $this->addressLine1 = func_get_arg(2);
            $this->city         = func_get_arg(3);
            $this->state        = func_get_arg(4);
            $this->postalCode   = func_get_arg(5);
            $this->country      = func_get_arg(6);
            $this->emailAddress = func_get_arg(7);
            $this->addressLine2 = func_get_arg(8);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['firstName']    = $this->firstName;
        $json['lastName']     = $this->lastName;
        $json['addressLine1'] = $this->addressLine1;
        $json['city']         = $this->city;
        $json['state']        = $this->state;
        $json['postalCode']   = $this->postalCode;
        $json['country']      = $this->country;
        $json['emailAddress'] = $this->emailAddress;
        $json['addressLine2'] = $this->addressLine2;

        return $json;
    }
}

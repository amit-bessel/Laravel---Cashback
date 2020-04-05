<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Create Account Request
 */
class CreateAccountRequestModel implements JsonSerializable
{
    /**
     * Account Identifier
     * @required
     * @var string $accountIdentifier public property
     */
    public $accountIdentifier;

    /**
     * Display Name
     * @required
     * @var string $displayName public property
     */
    public $displayName;

    /**
     * Contact Email
     * @required
     * @var string $contactEmail public property
     */
    public $contactEmail;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $accountIdentifier Initialization value for $this->accountIdentifier
     * @param string $displayName       Initialization value for $this->displayName
     * @param string $contactEmail      Initialization value for $this->contactEmail
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->accountIdentifier = func_get_arg(0);
            $this->displayName       = func_get_arg(1);
            $this->contactEmail      = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['accountIdentifier'] = $this->accountIdentifier;
        $json['displayName']       = $this->displayName;
        $json['contactEmail']      = $this->contactEmail;

        return $json;
    }
}

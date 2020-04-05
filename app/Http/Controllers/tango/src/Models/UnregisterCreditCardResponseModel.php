<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Unregister Credit Card Response
 */
class UnregisterCreditCardResponseModel implements JsonSerializable
{
    /**
     * @todo Write general description for this property
     * @required
     * @var string $createdDate public property
     */
    public $createdDate;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $message public property
     */
    public $message;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $token public property
     */
    public $token;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $createdDate Initialization value for $this->createdDate
     * @param string $message     Initialization value for $this->message
     * @param string $token       Initialization value for $this->token
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->createdDate = func_get_arg(0);
            $this->message     = func_get_arg(1);
            $this->token       = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['createdDate'] = $this->createdDate;
        $json['message']     = $this->message;
        $json['token']       = $this->token;

        return $json;
    }
}

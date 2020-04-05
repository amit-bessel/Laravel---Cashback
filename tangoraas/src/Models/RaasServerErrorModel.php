<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * RaaS 5xx Error
 */
class RaasServerErrorModel implements JsonSerializable
{
    /**
     * Error Message
     * @required
     * @var string $message public property
     */
    public $message;

    /**
     * Error Code
     * @required
     * @var integer $code public property
     */
    public $code;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $message Initialization value for $this->message
     * @param integer $code    Initialization value for $this->code
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->message = func_get_arg(0);
            $this->code    = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['message'] = $this->message;
        $json['code']    = $this->code;

        return $json;
    }
}

<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Response for Resend Order Call
 */
class ResendOrderResponseModel implements JsonSerializable
{
    /**
     * When the resend request was created
     * @required
     * @var string $createdAt public property
     */
    public $createdAt;

    /**
     * The order resend id
     * @required
     * @var integer $id public property
     */
    public $id;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $createdAt Initialization value for $this->createdAt
     * @param integer $id        Initialization value for $this->id
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->createdAt = func_get_arg(0);
            $this->id        = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['createdAt'] = $this->createdAt;
        $json['id']        = $this->id;

        return $json;
    }
}

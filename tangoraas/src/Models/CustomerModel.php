<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Customer Model
 */
class CustomerModel implements JsonSerializable
{
    /**
     * Customer Identifier
     * @required
     * @var string $customerIdentifier public property
     */
    public $customerIdentifier;

    /**
     * Display Name
     * @required
     * @var string $displayName public property
     */
    public $displayName;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Date Created
     * @required
     * @var string $createdAt public property
     */
    public $createdAt;

    /**
     * Accounts
     * @required
     * @var AccountSummaryModel[] $accounts public property
     */
    public $accounts;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $customerIdentifier Initialization value for $this->customerIdentifier
     * @param string $displayName        Initialization value for $this->displayName
     * @param string $status             Initialization value for $this->status
     * @param string $createdAt          Initialization value for $this->createdAt
     * @param array  $accounts           Initialization value for $this->accounts
     */
    public function __construct()
    {
        if (5 == func_num_args()) {
            $this->customerIdentifier = func_get_arg(0);
            $this->displayName        = func_get_arg(1);
            $this->status             = func_get_arg(2);
            $this->createdAt          = func_get_arg(3);
            $this->accounts           = func_get_arg(4);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['customerIdentifier'] = $this->customerIdentifier;
        $json['displayName']        = $this->displayName;
        $json['status']             = $this->status;
        $json['createdAt']          = $this->createdAt;
        $json['accounts']           = $this->accounts;

        return $json;
    }
}

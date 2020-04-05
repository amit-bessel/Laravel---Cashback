<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Account Summary Model
 */
class AccountSummaryModel implements JsonSerializable
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
     * Date Created
     * @required
     * @var string $createdAt public property
     */
    public $createdAt;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $accountIdentifier Initialization value for $this->accountIdentifier
     * @param string $displayName       Initialization value for $this->displayName
     * @param string $createdAt         Initialization value for $this->createdAt
     * @param string $status            Initialization value for $this->status
     */
    public function __construct()
    {
        if (4 == func_num_args()) {
            $this->accountIdentifier = func_get_arg(0);
            $this->displayName       = func_get_arg(1);
            $this->createdAt         = func_get_arg(2);
            $this->status            = func_get_arg(3);
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
        $json['createdAt']         = $this->createdAt;
        $json['status']            = $this->status;

        return $json;
    }
}

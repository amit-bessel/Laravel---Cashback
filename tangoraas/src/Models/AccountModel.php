<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Account Model
 */
class AccountModel implements JsonSerializable
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
     * Currency Code
     * @required
     * @var string $currencyCode public property
     */
    public $currencyCode;

    /**
     * Current Balance
     * @required
     * @var double $currentBalance public property
     */
    public $currentBalance;

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
     * Contact Email
     * @var string|null $contactEmail public property
     */
    public $contactEmail;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $accountIdentifier Initialization value for $this->accountIdentifier
     * @param string $displayName       Initialization value for $this->displayName
     * @param string $currencyCode      Initialization value for $this->currencyCode
     * @param double $currentBalance    Initialization value for $this->currentBalance
     * @param string $createdAt         Initialization value for $this->createdAt
     * @param string $status            Initialization value for $this->status
     * @param string $contactEmail      Initialization value for $this->contactEmail
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 7:
                $this->accountIdentifier = func_get_arg(0);
                $this->displayName       = func_get_arg(1);
                $this->currencyCode      = func_get_arg(2);
                $this->currentBalance    = func_get_arg(3);
                $this->createdAt         = func_get_arg(4);
                $this->status            = func_get_arg(5);
                $this->contactEmail      = func_get_arg(6);
                break;

            default:
                $this->currencyCode = 'USD';
                break;
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
        $json['currencyCode']      = $this->currencyCode;
        $json['currentBalance']    = $this->currentBalance;
        $json['createdAt']         = $this->createdAt;
        $json['status']            = $this->status;
        $json['contactEmail']      = $this->contactEmail;

        return $json;
    }
}

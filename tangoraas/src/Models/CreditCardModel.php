<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Credit Card
 */
class CreditCardModel implements JsonSerializable
{
    /**
     * @todo Write general description for this property
     * @required
     * @var string $customerIdentifier public property
     */
    public $customerIdentifier;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $accountIdentifier public property
     */
    public $accountIdentifier;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $token public property
     */
    public $token;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $label public property
     */
    public $label;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $lastFourDigits public property
     */
    public $lastFourDigits;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $expirationDate public property
     */
    public $expirationDate;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $createdDate public property
     */
    public $createdDate;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $activationDate public property
     */
    public $activationDate;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $customerIdentifier Initialization value for $this->customerIdentifier
     * @param string $accountIdentifier  Initialization value for $this->accountIdentifier
     * @param string $token              Initialization value for $this->token
     * @param string $label              Initialization value for $this->label
     * @param string $lastFourDigits     Initialization value for $this->lastFourDigits
     * @param string $expirationDate     Initialization value for $this->expirationDate
     * @param string $status             Initialization value for $this->status
     * @param string $createdDate        Initialization value for $this->createdDate
     * @param string $activationDate     Initialization value for $this->activationDate
     */
    public function __construct()
    {
        if (9 == func_num_args()) {
            $this->customerIdentifier = func_get_arg(0);
            $this->accountIdentifier  = func_get_arg(1);
            $this->token              = func_get_arg(2);
            $this->label              = func_get_arg(3);
            $this->lastFourDigits     = func_get_arg(4);
            $this->expirationDate     = func_get_arg(5);
            $this->status             = func_get_arg(6);
            $this->createdDate        = func_get_arg(7);
            $this->activationDate     = func_get_arg(8);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['customerIdentifier'] = $this->customerIdentifier;
        $json['accountIdentifier']  = $this->accountIdentifier;
        $json['token']              = $this->token;
        $json['label']              = $this->label;
        $json['lastFourDigits']     = $this->lastFourDigits;
        $json['expirationDate']     = $this->expirationDate;
        $json['status']             = $this->status;
        $json['createdDate']        = $this->createdDate;
        $json['activationDate']     = $this->activationDate;

        return $json;
    }
}

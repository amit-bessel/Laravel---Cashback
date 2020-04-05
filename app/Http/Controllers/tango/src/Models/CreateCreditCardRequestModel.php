<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Register Credit Card Request
 */
class CreateCreditCardRequestModel implements JsonSerializable
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
     * @var string $label public property
     */
    public $label;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $ipAddress public property
     */
    public $ipAddress;

    /**
     * @todo Write general description for this property
     * @required
     * @var NewCreditCardModel $creditCard public property
     */
    public $creditCard;

    /**
     * @todo Write general description for this property
     * @required
     * @var BillingAddressModel $billingAddress public property
     */
    public $billingAddress;

    /**
     * Constructor to set initial or default values of member properties
     * @param string              $customerIdentifier Initialization value for $this->customerIdentifier
     * @param string              $accountIdentifier  Initialization value for $this->accountIdentifier
     * @param string              $label              Initialization value for $this->label
     * @param string              $ipAddress          Initialization value for $this->ipAddress
     * @param NewCreditCardModel  $creditCard         Initialization value for $this->creditCard
     * @param BillingAddressModel $billingAddress     Initialization value for $this->billingAddress
     */
    public function __construct()
    {
        if (6 == func_num_args()) {
            $this->customerIdentifier = func_get_arg(0);
            $this->accountIdentifier  = func_get_arg(1);
            $this->label              = func_get_arg(2);
            $this->ipAddress          = func_get_arg(3);
            $this->creditCard         = func_get_arg(4);
            $this->billingAddress     = func_get_arg(5);
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
        $json['label']              = $this->label;
        $json['ipAddress']          = $this->ipAddress;
        $json['creditCard']         = $this->creditCard;
        $json['billingAddress']     = $this->billingAddress;

        return $json;
    }
}

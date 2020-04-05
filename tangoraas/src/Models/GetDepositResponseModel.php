<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Get Deposit Response
 */
class GetDepositResponseModel implements JsonSerializable
{
    /**
     * @todo Write general description for this property
     * @required
     * @var double $amount public property
     */
    public $amount;

    /**
     * @todo Write general description for this property
     * @required
     * @var double $amountCharged public property
     */
    public $amountCharged;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $createdDate public property
     */
    public $createdDate;

    /**
     * @todo Write general description for this property
     * @required
     * @var double $feePercent public property
     */
    public $feePercent;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $referenceDepositID public property
     */
    public $referenceDepositID;

    /**
     * @todo Write general description for this property
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Constructor to set initial or default values of member properties
     * @param double $amount             Initialization value for $this->amount
     * @param double $amountCharged      Initialization value for $this->amountCharged
     * @param string $createdDate        Initialization value for $this->createdDate
     * @param double $feePercent         Initialization value for $this->feePercent
     * @param string $referenceDepositID Initialization value for $this->referenceDepositID
     * @param string $status             Initialization value for $this->status
     */
    public function __construct()
    {
        if (6 == func_num_args()) {
            $this->amount             = func_get_arg(0);
            $this->amountCharged      = func_get_arg(1);
            $this->createdDate        = func_get_arg(2);
            $this->feePercent         = func_get_arg(3);
            $this->referenceDepositID = func_get_arg(4);
            $this->status             = func_get_arg(5);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['amount']             = $this->amount;
        $json['amountCharged']      = $this->amountCharged;
        $json['createdDate']        = $this->createdDate;
        $json['feePercent']         = $this->feePercent;
        $json['referenceDepositID'] = $this->referenceDepositID;
        $json['status']             = $this->status;

        return $json;
    }
}

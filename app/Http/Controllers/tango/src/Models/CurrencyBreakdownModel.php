<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Amount/Currency Breakdown
 */
class CurrencyBreakdownModel implements JsonSerializable
{
    /**
     * Currency Code
     * @var string|null $currencyCode public property
     */
    public $currencyCode;

    /**
     * Exchange Rate
     * @var double|null $exchangeRate public property
     */
    public $exchangeRate;

    /**
     * Fee
     * @var double|null $fee public property
     */
    public $fee;

    /**
     * Total
     * @var double|null $total public property
     */
    public $total;

    /**
     * Value
     * @var double|null $value public property
     */
    public $value;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $currencyCode Initialization value for $this->currencyCode
     * @param double $exchangeRate Initialization value for $this->exchangeRate
     * @param double $fee          Initialization value for $this->fee
     * @param double $total        Initialization value for $this->total
     * @param double $value        Initialization value for $this->value
     */
    public function __construct()
    {
        if (5 == func_num_args()) {
            $this->currencyCode = func_get_arg(0);
            $this->exchangeRate = func_get_arg(1);
            $this->fee          = func_get_arg(2);
            $this->total        = func_get_arg(3);
            $this->value        = func_get_arg(4);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['currencyCode'] = $this->currencyCode;
        $json['exchangeRate'] = $this->exchangeRate;
        $json['fee']          = $this->fee;
        $json['total']        = $this->total;
        $json['value']        = $this->value;

        return $json;
    }
}

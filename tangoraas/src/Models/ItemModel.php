<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Item Model
 */
class ItemModel implements JsonSerializable
{
    /**
     * UTID
     * @required
     * @var string $utid public property
     */
    public $utid;

    /**
     * Reward Name
     * @required
     * @var string $rewardName public property
     */
    public $rewardName;

    /**
     * Currency Code
     * @required
     * @var string $currencyCode public property
     */
    public $currencyCode;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Value Type (fixed/variable)
     * @required
     * @var string $valueType public property
     */
    public $valueType;

    /**
     * Reward Type
     * @required
     * @var string $rewardType public property
     */
    public $rewardType;

    /**
     * Date Created
     * @required
     * @var string $createdDate public property
     */
    public $createdDate;

    /**
     * Last Updated
     * @required
     * @var string $lastUpdateDate public property
     */
    public $lastUpdateDate;

    /**
     * Countries
     * @required
     * @var array $countries public property
     */
    public $countries;

    /**
     * Minimum Value (for variable value items)
     * @var double|null $minValue public property
     */
    public $minValue;

    /**
     * Maximum Value (for variable value items)
     * @var double|null $maxValue public property
     */
    public $maxValue;

    /**
     * Face Value
     * @var double|null $faceValue public property
     */
    public $faceValue;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $utid           Initialization value for $this->utid
     * @param string $rewardName     Initialization value for $this->rewardName
     * @param string $currencyCode   Initialization value for $this->currencyCode
     * @param string $status         Initialization value for $this->status
     * @param string $valueType      Initialization value for $this->valueType
     * @param string $rewardType     Initialization value for $this->rewardType
     * @param string $createdDate    Initialization value for $this->createdDate
     * @param string $lastUpdateDate Initialization value for $this->lastUpdateDate
     * @param array  $countries      Initialization value for $this->countries
     * @param double $minValue       Initialization value for $this->minValue
     * @param double $maxValue       Initialization value for $this->maxValue
     * @param double $faceValue      Initialization value for $this->faceValue
     */
    public function __construct()
    {
        if (12 == func_num_args()) {
            $this->utid           = func_get_arg(0);
            $this->rewardName     = func_get_arg(1);
            $this->currencyCode   = func_get_arg(2);
            $this->status         = func_get_arg(3);
            $this->valueType      = func_get_arg(4);
            $this->rewardType     = func_get_arg(5);
            $this->createdDate    = func_get_arg(6);
            $this->lastUpdateDate = func_get_arg(7);
            $this->countries      = func_get_arg(8);
            $this->minValue       = func_get_arg(9);
            $this->maxValue       = func_get_arg(10);
            $this->faceValue      = func_get_arg(11);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['utid']           = $this->utid;
        $json['rewardName']     = $this->rewardName;
        $json['currencyCode']   = $this->currencyCode;
        $json['status']         = $this->status;
        $json['valueType']      = $this->valueType;
        $json['rewardType']     = $this->rewardType;
        $json['createdDate']    = $this->createdDate;
        $json['lastUpdateDate'] = $this->lastUpdateDate;
        $json['countries']      = $this->countries;
        $json['minValue']       = $this->minValue;
        $json['maxValue']       = $this->maxValue;
        $json['faceValue']      = $this->faceValue;

        return $json;
    }
}

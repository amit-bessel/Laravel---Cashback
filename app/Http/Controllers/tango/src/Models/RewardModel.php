<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Reward Model
 */
class RewardModel implements JsonSerializable
{
    /**
     * A map of reward credentials
     * @required
     * @var array $credentials public property
     */
    public $credentials;

    /**
     * An array of reward credentials
     * @required
     * @var RewardCredentialModel[] $credentialList public property
     */
    public $credentialList;

    /**
     * Reward redemption instructions
     * @required
     * @var string $redemptionInstructions public property
     */
    public $redemptionInstructions;

    /**
     * Constructor to set initial or default values of member properties
     * @param array  $credentials            Initialization value for $this->credentials
     * @param array  $credentialList         Initialization value for $this->credentialList
     * @param string $redemptionInstructions Initialization value for $this->redemptionInstructions
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->credentials            = func_get_arg(0);
            $this->credentialList         = func_get_arg(1);
            $this->redemptionInstructions = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['credentials']            = $this->credentials;
        $json['credentialList']         = $this->credentialList;
        $json['redemptionInstructions'] = $this->redemptionInstructions;

        return $json;
    }
}

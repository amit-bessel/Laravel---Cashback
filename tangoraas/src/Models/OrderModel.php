<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Order Model
 */
class OrderModel implements JsonSerializable
{
    /**
     * Account Identifier
     * @required
     * @var string $accountIdentifier public property
     */
    public $accountIdentifier;

    /**
     * Amount Charged
     * @required
     * @var CurrencyBreakdownModel $amountCharged public property
     */
    public $amountCharged;

    /**
     * Created At
     * @required
     * @var string $createdAt public property
     */
    public $createdAt;

    /**
     * Customer Identifier
     * @required
     * @var string $customerIdentifier public property
     */
    public $customerIdentifier;

    /**
     * Denomination
     * @required
     * @var CurrencyBreakdownModel $denomination public property
     */
    public $denomination;

    /**
     * Reference Order ID
     * @required
     * @var string $referenceOrderID public property
     */
    public $referenceOrderID;

    /**
     * Reward
     * @required
     * @var RewardModel $reward public property
     */
    public $reward;

    /**
     * Reward Name
     * @required
     * @var string $rewardName public property
     */
    public $rewardName;

    /**
     * Send Email
     * @required
     * @var bool $sendEmail public property
     */
    public $sendEmail;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * UTID
     * @required
     * @var string $utid public property
     */
    public $utid;

    /**
     * Campaign
     * @var string|null $campaign public property
     */
    public $campaign;

    /**
     * Email Subject
     * @var string|null $emailSubject public property
     */
    public $emailSubject;

    /**
     * External Reference ID
     * @var string|null $externalRefID public property
     */
    public $externalRefID;

    /**
     * Message
     * @var string|null $message public property
     */
    public $message;

    /**
     * Notes
     * @var string|null $notes public property
     */
    public $notes;

    /**
     * Recipient
     * @var NameEmailModel|null $recipient public property
     */
    public $recipient;

    /**
     * Sender
     * @var NameEmailModel|null $sender public property
     */
    public $sender;

    /**
     * Constructor to set initial or default values of member properties
     * @param string                 $accountIdentifier  Initialization value for $this->accountIdentifier
     * @param CurrencyBreakdownModel $amountCharged      Initialization value for $this->amountCharged
     * @param string                 $createdAt          Initialization value for $this->createdAt
     * @param string                 $customerIdentifier Initialization value for $this->customerIdentifier
     * @param CurrencyBreakdownModel $denomination       Initialization value for $this->denomination
     * @param string                 $referenceOrderID   Initialization value for $this->referenceOrderID
     * @param RewardModel            $reward             Initialization value for $this->reward
     * @param string                 $rewardName         Initialization value for $this->rewardName
     * @param bool                   $sendEmail          Initialization value for $this->sendEmail
     * @param string                 $status             Initialization value for $this->status
     * @param string                 $utid               Initialization value for $this->utid
     * @param string                 $campaign           Initialization value for $this->campaign
     * @param string                 $emailSubject       Initialization value for $this->emailSubject
     * @param string                 $externalRefID      Initialization value for $this->externalRefID
     * @param string                 $message            Initialization value for $this->message
     * @param string                 $notes              Initialization value for $this->notes
     * @param NameEmailModel         $recipient          Initialization value for $this->recipient
     * @param NameEmailModel         $sender             Initialization value for $this->sender
     */
    public function __construct()
    {
        if (18 == func_num_args()) {
            $this->accountIdentifier  = func_get_arg(0);
            $this->amountCharged      = func_get_arg(1);
            $this->createdAt          = func_get_arg(2);
            $this->customerIdentifier = func_get_arg(3);
            $this->denomination       = func_get_arg(4);
            $this->referenceOrderID   = func_get_arg(5);
            $this->reward             = func_get_arg(6);
            $this->rewardName         = func_get_arg(7);
            $this->sendEmail          = func_get_arg(8);
            $this->status             = func_get_arg(9);
            $this->utid               = func_get_arg(10);
            $this->campaign           = func_get_arg(11);
            $this->emailSubject       = func_get_arg(12);
            $this->externalRefID      = func_get_arg(13);
            $this->message            = func_get_arg(14);
            $this->notes              = func_get_arg(15);
            $this->recipient          = func_get_arg(16);
            $this->sender             = func_get_arg(17);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['accountIdentifier']  = $this->accountIdentifier;
        $json['amountCharged']      = $this->amountCharged;
        $json['createdAt']          = $this->createdAt;
        $json['customerIdentifier'] = $this->customerIdentifier;
        $json['denomination']       = $this->denomination;
        $json['referenceOrderID']   = $this->referenceOrderID;
        $json['reward']             = $this->reward;
        $json['rewardName']         = $this->rewardName;
        $json['sendEmail']          = $this->sendEmail;
        $json['status']             = $this->status;
        $json['utid']               = $this->utid;
        $json['campaign']           = $this->campaign;
        $json['emailSubject']       = $this->emailSubject;
        $json['externalRefID']      = $this->externalRefID;
        $json['message']            = $this->message;
        $json['notes']              = $this->notes;
        $json['recipient']          = $this->recipient;
        $json['sender']             = $this->sender;

        return $json;
    }
}

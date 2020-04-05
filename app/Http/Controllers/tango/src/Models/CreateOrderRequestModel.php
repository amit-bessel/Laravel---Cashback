<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Create Order Request
 */
class CreateOrderRequestModel implements JsonSerializable
{
    /**
     * Account Identifier
     * @required
     * @var string $accountIdentifier public property
     */
    public $accountIdentifier;

    /**
     * Amount
     * @required
     * @var double $amount public property
     */
    public $amount;

    /**
     * Customer Identifier
     * @required
     * @var string $customerIdentifier public property
     */
    public $customerIdentifier;

    /**
     * Send Email
     * @required
     * @var bool $sendEmail public property
     */
    public $sendEmail;

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
     * Email Message
     * @var string|null $message public property
     */
    public $message;

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
     * Notes
     * @var string|null $notes public property
     */
    public $notes;

    /**
     * Constructor to set initial or default values of member properties
     * @param string         $accountIdentifier  Initialization value for $this->accountIdentifier
     * @param double         $amount             Initialization value for $this->amount
     * @param string         $customerIdentifier Initialization value for $this->customerIdentifier
     * @param bool           $sendEmail          Initialization value for $this->sendEmail
     * @param string         $utid               Initialization value for $this->utid
     * @param string         $campaign           Initialization value for $this->campaign
     * @param string         $emailSubject       Initialization value for $this->emailSubject
     * @param string         $externalRefID      Initialization value for $this->externalRefID
     * @param string         $message            Initialization value for $this->message
     * @param NameEmailModel $recipient          Initialization value for $this->recipient
     * @param NameEmailModel $sender             Initialization value for $this->sender
     * @param string         $notes              Initialization value for $this->notes
     */
    public function __construct()
    {
        if (12 == func_num_args()) {
            $this->accountIdentifier  = func_get_arg(0);
            $this->amount             = func_get_arg(1);
            $this->customerIdentifier = func_get_arg(2);
            $this->sendEmail          = func_get_arg(3);
            $this->utid               = func_get_arg(4);
            $this->campaign           = func_get_arg(5);
            $this->emailSubject       = func_get_arg(6);
            $this->externalRefID      = func_get_arg(7);
            $this->message            = func_get_arg(8);
            $this->recipient          = func_get_arg(9);
            $this->sender             = func_get_arg(10);
            $this->notes              = func_get_arg(11);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['accountIdentifier']  = $this->accountIdentifier;
        $json['amount']             = $this->amount;
        $json['customerIdentifier'] = $this->customerIdentifier;
        $json['sendEmail']          = $this->sendEmail;
        $json['utid']               = $this->utid;
        $json['campaign']           = $this->campaign;
        $json['emailSubject']       = $this->emailSubject;
        $json['externalRefID']      = $this->externalRefID;
        $json['message']            = $this->message;
        $json['recipient']          = $this->recipient;
        $json['sender']             = $this->sender;
        $json['notes']              = $this->notes;

        return $json;
    }
}

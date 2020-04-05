<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Brand Model
 */
class BrandModel implements JsonSerializable
{
    /**
     * Brand Key
     * @required
     * @var string $brandKey public property
     */
    public $brandKey;

    /**
     * Brand Name
     * @required
     * @var string $brandName public property
     */
    public $brandName;

    /**
     * Disclaimer
     * @required
     * @var string $disclaimer public property
     */
    public $disclaimer;

    /**
     * Description
     * @required
     * @var string $description public property
     */
    public $description;

    /**
     * Short Description
     * @required
     * @var string $shortDescription public property
     */
    public $shortDescription;

    /**
     * Terms
     * @required
     * @var string $terms public property
     */
    public $terms;

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
     * Image URLs
     * @required
     * @var array $imageUrls public property
     */
    public $imageUrls;

    /**
     * Status
     * @required
     * @var string $status public property
     */
    public $status;

    /**
     * Items
     * @required
     * @var ItemModel[] $items public property
     */
    public $items;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $brandKey         Initialization value for $this->brandKey
     * @param string $brandName        Initialization value for $this->brandName
     * @param string $disclaimer       Initialization value for $this->disclaimer
     * @param string $description      Initialization value for $this->description
     * @param string $shortDescription Initialization value for $this->shortDescription
     * @param string $terms            Initialization value for $this->terms
     * @param string $createdDate      Initialization value for $this->createdDate
     * @param string $lastUpdateDate   Initialization value for $this->lastUpdateDate
     * @param array  $imageUrls        Initialization value for $this->imageUrls
     * @param string $status           Initialization value for $this->status
     * @param array  $items            Initialization value for $this->items
     */
    public function __construct()
    {
        if (11 == func_num_args()) {
            $this->brandKey         = func_get_arg(0);
            $this->brandName        = func_get_arg(1);
            $this->disclaimer       = func_get_arg(2);
            $this->description      = func_get_arg(3);
            $this->shortDescription = func_get_arg(4);
            $this->terms            = func_get_arg(5);
            $this->createdDate      = func_get_arg(6);
            $this->lastUpdateDate   = func_get_arg(7);
            $this->imageUrls        = func_get_arg(8);
            $this->status           = func_get_arg(9);
            $this->items            = func_get_arg(10);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['brandKey']         = $this->brandKey;
        $json['brandName']        = $this->brandName;
        $json['disclaimer']       = $this->disclaimer;
        $json['description']      = $this->description;
        $json['shortDescription'] = $this->shortDescription;
        $json['terms']            = $this->terms;
        $json['createdDate']      = $this->createdDate;
        $json['lastUpdateDate']   = $this->lastUpdateDate;
        $json['imageUrls']        = $this->imageUrls;
        $json['status']           = $this->status;
        $json['items']            = $this->items;

        return $json;
    }
}

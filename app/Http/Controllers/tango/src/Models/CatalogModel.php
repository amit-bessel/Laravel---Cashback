<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Models;

use JsonSerializable;

/**
 * Catalog Model
 */
class CatalogModel implements JsonSerializable
{
    /**
     * The name of your catalog
     * @required
     * @var string $catalogName public property
     */
    public $catalogName;

    /**
     * The brands in your catalog
     * @required
     * @var BrandModel[] $brands public property
     */
    public $brands;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $catalogName Initialization value for $this->catalogName
     * @param array  $brands      Initialization value for $this->brands
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->catalogName = func_get_arg(0);
            $this->brands      = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['catalogName'] = $this->catalogName;
        $json['brands']      = $this->brands;

        return $json;
    }
}

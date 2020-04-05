<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib;

use RaasLib\Controllers;

/**
 * Raas client class
 */
class RaasClient
{
    /**
     * Constructor with authentication and configuration parameters
     */
    public function __construct(
        $platformName = null,
        $platformKey = null
    ) {
        Configuration::$platformName = $platformName ? $platformName : Configuration::$platformName;
        Configuration::$platformKey = $platformKey ? $platformKey : Configuration::$platformKey;
    }
    /**
     * Singleton access to Accounts controller
     * @return Controllers\AccountsController The *Singleton* instance
     */
    public function getAccounts()
    {
        return Controllers\AccountsController::getInstance();
    }
    /**
     * Singleton access to Catalog controller
     * @return Controllers\CatalogController The *Singleton* instance
     */
    public function getCatalog()
    {
        return Controllers\CatalogController::getInstance();
    }
    /**
     * Singleton access to Orders controller
     * @return Controllers\OrdersController The *Singleton* instance
     */
    public function getOrders()
    {
        return Controllers\OrdersController::getInstance();
    }
    /**
     * Singleton access to Customers controller
     * @return Controllers\CustomersController The *Singleton* instance
     */
    public function getCustomers()
    {
        return Controllers\CustomersController::getInstance();
    }
    /**
     * Singleton access to ExchangeRates controller
     * @return Controllers\ExchangeRatesController The *Singleton* instance
     */
    public function getExchangeRates()
    {
        return Controllers\ExchangeRatesController::getInstance();
    }
    /**
     * Singleton access to Status controller
     * @return Controllers\StatusController The *Singleton* instance
     */
    public function getStatus()
    {
        return Controllers\StatusController::getInstance();
    }
    /**
     * Singleton access to Fund controller
     * @return Controllers\FundController The *Singleton* instance
     */
    public function getFund()
    {
        return Controllers\FundController::getInstance();
    }
}




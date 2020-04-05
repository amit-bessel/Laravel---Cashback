<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib;

/**
 * All configuration including auth info and base URI for the API access
 * are configured in this class.
 */
class Configuration
{
    /**
     * The environment being used'
     * @var string
     */
    public static $environment = Environments::SANDBOX;

    /**
     * RaaS v2 API Platform Name
     * @var string
     */
    public static $platformName = 'QAPlatform2';

    /**
     * RaaS v2 API Platform Key
     * @var string
     */
    public static $platformKey = 'apYPfT6HNONpDRUj3CLGWYt7gvIHONpDRUYPfT6Hj';

    /**
     * Get the base uri for a given server in the current environment
     * @param  string $server Server name
     * @return string         Base URI
     */
    public static function getBaseUri($server = Servers::DEFAULT_)
    {
        return APIHelper::appendUrlWithTemplateParameters(
            static::$environmentsMap[static::$environment][$server],
            array(
            )
        );
    }

    /**
     * A map of all baseurls used in different environments and servers
     * @var array
     */
    private static $environmentsMap = array(
        Environments::SANDBOX => array(
            Servers::DEFAULT_ => 'https://integration-api.tangocard.com/raas/v2',
        ),
        Environments::PRODUCTION => array(
            Servers::DEFAULT_ => 'https://api.tangocard.com/raas/v2',
        ),
        Environments::QA => array(
            Servers::DEFAULT_ => 'https://qa-api.tangocard.com/raas/v2',
        ),
        Environments::GAMMA => array(
            Servers::DEFAULT_ => 'https://gamma-api.tangocard.com/raas/v2',
        ),
        Environments::LOCAL => array(
            Servers::DEFAULT_ => 'http://raastango.cc:8080/v2',
        ),
    );
}

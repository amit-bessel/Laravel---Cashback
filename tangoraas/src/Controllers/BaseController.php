<?php
/*
 * Raas
 *
 * This file was automatically generated for Tango Card, Inc. by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace RaasLib\Controllers;

use RaasLib\Http\HttpCallBack;
use RaasLib\Http\HttpContext;
use RaasLib\Http\HttpResponse;
use RaasLib\APIException;
use RaasLib\Exceptions;
use \apimatic\jsonmapper\JsonMapper;
use Unirest\Request;

/**
* Base controller
*/
class BaseController
{
    /**
     * HttpCallBack instance associated with this controller
     * @var HttpCallBack
     */
    private $httpCallBack = null;

     /**
     * Constructor that sets the timeout of requests
     */
    function __construct(){
        Request::timeout(15);
    }

    /**
     * Set HttpCallBack for this controller
     * @param HttpCallBack $httpCallBack Http Callbacks called before/after each API call
     */
    public function setHttpCallBack(HttpCallBack $httpCallBack)
    {
        $this->httpCallBack = $httpCallBack;
    }

    /**
     * Get HttpCallBack for this controller
     * @return HttpCallBack The HttpCallBack object set for this controller
     */
    public function getHttpCallBack()
    {
        return $this->httpCallBack;
    }

    /**
     * Get a new JsonMapper instance for mapping objects
     * @return \apimatic\jsonmapper\JsonMapper JsonMapper instance
     */
    protected function getJsonMapper()
    {
        $mapper = new JsonMapper();
        return $mapper;
    }

    protected function validateResponse(HttpResponse $response, HttpContext $_httpContext)
    {
        if ($response->getStatusCode() == 400) {
            throw new Exceptions\RaasClientException('Bad Request', $_httpContext);
        }

        if ($response->getStatusCode() == 401) {
            throw new Exceptions\RaasGenericException('Unauthorized - Invalid Credentials', $_httpContext);
        }

        if ($response->getStatusCode() == 403) {
            throw new Exceptions\RaasClientException('Forbidden', $_httpContext);
        }

        if ($response->getStatusCode() == 404) {
            throw new Exceptions\RaasGenericException('Not Found', $_httpContext);
        }

        if ($response->getStatusCode() == 409) {
            throw new Exceptions\RaasClientException('Conflict', $_httpContext);
        }

        if ($response->getStatusCode() == 500) {
            throw new Exceptions\RaasServerException('Internal Server Error - Retry Later', $_httpContext);
        }

        if ($response->getStatusCode() == 503) {
            throw new Exceptions\RaasServerException('Service Unavailable - Retry Later', $_httpContext);
        }

        if (($response->getStatusCode() < 200) || ($response->getStatusCode() > 208)) { //[200,208] = HTTP OK
            throw new Exceptions\RaasGenericException('API Error', $_httpContext);
        }
    }
}

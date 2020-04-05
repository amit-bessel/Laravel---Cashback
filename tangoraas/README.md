# Getting started

With this RESTful API you can integrate a global reward or incentive program into your app or platform. If you have any questions or if you'd like to receive your own credentials, please contact us at devsupport@tangocard.com.

## How to Build

The generated code has dependencies over external libraries like UniRest. These dependencies are defined in the ```composer.json``` file that comes with the SDK. 
To resolve these dependencies, we use the Composer package manager which requires PHP greater than 5.3.2 installed in your system. 
Visit [https://getcomposer.org/download/](https://getcomposer.org/download/) to download the installer file for Composer and run it in your system. 
Open command prompt and type ```composer --version```. This should display the current version of the Composer installed if the installation was successful.

* Using command line, navigate to the directory containing the generated files (including ```composer.json```) for the SDK. 
* Run the command ```composer install```. This should install all the required dependencies and create the ```vendor``` directory in your project directory.

![Building SDK - Step 1](https://apidocs.io/illustration/php?step=installDependencies&workspaceFolder=Raas-PHP)

### [For Windows Users Only] Configuring CURL Certificate Path in php.ini

CURL used to include a list of accepted CAs, but no longer bundles ANY CA certs. So by default it will reject all SSL certificates as unverifiable. You will have to get your CA's cert and point curl at it. The steps are as follows:

1. Download the certificate bundle (.pem file) from [https://curl.haxx.se/docs/caextract.html](https://curl.haxx.se/docs/caextract.html) on to your system.
2. Add curl.cainfo = "PATH_TO/cacert.pem" to your php.ini file located in your php installation. “PATH_TO” must be an absolute path containing the .pem file.

```ini
[curl]
; A default value for the CURLOPT_CAINFO option. This is required to be an
; absolute path.
;curl.cainfo =
```

## How to Use

The following section explains how to use the Raas library in a new project.

### 1. Open Project in an IDE

Open an IDE for PHP like PhpStorm. The basic workflow presented here is also applicable if you prefer using a different editor or IDE.

![Open project in PHPStorm - Step 1](https://apidocs.io/illustration/php?step=openIDE&workspaceFolder=Raas-PHP)

Click on ```Open``` in PhpStorm to browse to your generated SDK directory and then click ```OK```.

![Open project in PHPStorm - Step 2](https://apidocs.io/illustration/php?step=openProject0&workspaceFolder=Raas-PHP)     

### 2. Add a new Test Project

Create a new directory by right clicking on the solution name as shown below:

![Add a new project in PHPStorm - Step 1](https://apidocs.io/illustration/php?step=createDirectory&workspaceFolder=Raas-PHP)

Name the directory as "test"

![Add a new project in PHPStorm - Step 2](https://apidocs.io/illustration/php?step=nameDirectory&workspaceFolder=Raas-PHP)
   
Add a PHP file to this project

![Add a new project in PHPStorm - Step 3](https://apidocs.io/illustration/php?step=createFile&workspaceFolder=Raas-PHP)

Name it "testSDK"

![Add a new project in PHPStorm - Step 4](https://apidocs.io/illustration/php?step=nameFile&workspaceFolder=Raas-PHP)

Depending on your project setup, you might need to include composer's autoloader in your PHP code to enable auto loading of classes.

```PHP
require_once "../vendor/autoload.php";
```

It is important that the path inside require_once correctly points to the file ```autoload.php``` inside the vendor directory created during dependency installations.

![Add a new project in PHPStorm - Step 4](https://apidocs.io/illustration/php?step=projectFiles&workspaceFolder=Raas-PHP)

After this you can add code to initialize the client library and acquire the instance of a Controller class. Sample code to initialize the client library and using controller methods is given in the subsequent sections.

### 3. Run the Test Project

To run your project you must set the Interpreter for your project. Interpreter is the PHP engine installed on your computer.

Open ```Settings``` from ```File``` menu.

![Run Test Project - Step 1](https://apidocs.io/illustration/php?step=openSettings&workspaceFolder=Raas-PHP)

Select ```PHP``` from within ```Languages & Frameworks```

![Run Test Project - Step 2](https://apidocs.io/illustration/php?step=setInterpreter0&workspaceFolder=Raas-PHP)

Browse for Interpreters near the ```Interpreter``` option and choose your interpreter.

![Run Test Project - Step 3](https://apidocs.io/illustration/php?step=setInterpreter1&workspaceFolder=Raas-PHP)

Once the interpreter is selected, click ```OK```

![Run Test Project - Step 4](https://apidocs.io/illustration/php?step=setInterpreter2&workspaceFolder=Raas-PHP)

To run your project, right click on your PHP file inside your Test project and click on ```Run```

![Run Test Project - Step 5](https://apidocs.io/illustration/php?step=runProject&workspaceFolder=Raas-PHP)

## How to Test

Unit tests in this SDK can be run using PHPUnit. 

1. First install the dependencies using composer including the `require-dev` dependencies.
2. Run `vendor\bin\phpunit --verbose` from commandline to execute tests. If you have 
   installed PHPUnit globally, run tests using `phpunit --verbose` instead.

You can change the PHPUnit test configuration in the `phpunit.xml` file.

## Initialization

### Authentication
In order to setup authentication and initialization of the API client, you need the following information.

| Parameter | Description |
|-----------|-------------|
| platformName | RaaS v2 API Platform Name |
| platformKey | RaaS v2 API Platform Key |



API client can be initialized as following.

```php
$platformName = 'QAPlatform2'; // RaaS v2 API Platform Name
$platformKey = 'apYPfT6HNONpDRUj3CLGWYt7gvIHONpDRUYPfT6Hj'; // RaaS v2 API Platform Key

$client = new RaasLib\RaasClient($platformName, $platformKey);
```


# Class Reference

## <a name="list_of_controllers"></a>List of Controllers

* [AccountsController](#accounts_controller)
* [CatalogController](#catalog_controller)
* [OrdersController](#orders_controller)
* [CustomersController](#customers_controller)
* [ExchangeRatesController](#exchange_rates_controller)
* [StatusController](#status_controller)
* [FundController](#fund_controller)

## <a name="accounts_controller"></a>![Class: ](https://apidocs.io/img/class.png ".AccountsController") AccountsController

### Get singleton instance

The singleton instance of the ``` AccountsController ``` class can be accessed from the API Client.

```php
$accounts = $client->getAccounts();
```

### <a name="get_account"></a>![Method: ](https://apidocs.io/img/method.png ".AccountsController.getAccount") getAccount

> Get an account


```php
function getAccount($accountIdentifier)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| accountIdentifier |  ``` Required ```  | Account Identifier |



#### Example Usage

```php
$accountIdentifier = 'accountIdentifier';

$result = $accounts->getAccount($accountIdentifier);

```


### <a name="get_all_accounts"></a>![Method: ](https://apidocs.io/img/method.png ".AccountsController.getAllAccounts") getAllAccounts

> Gets all accounts under the platform


```php
function getAllAccounts()
```

#### Example Usage

```php

$result = $accounts->getAllAccounts();

```


### <a name="create_account"></a>![Method: ](https://apidocs.io/img/method.png ".AccountsController.createAccount") createAccount

> Create an account under a given customer


```php
function createAccount(
        $customerIdentifier,
        $body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| customerIdentifier |  ``` Required ```  | Customer Identifier |
| body |  ``` Required ```  | Request Body |



#### Example Usage

```php
$customerIdentifier = 'customerIdentifier';
$body = new CreateAccountRequestModel();

$result = $accounts->createAccount($customerIdentifier, $body);

```


### <a name="get_accounts_by_customer"></a>![Method: ](https://apidocs.io/img/method.png ".AccountsController.getAccountsByCustomer") getAccountsByCustomer

> Gets a list of accounts for a given customer


```php
function getAccountsByCustomer($customerIdentifier)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| customerIdentifier |  ``` Required ```  | Customer Identifier |



#### Example Usage

```php
$customerIdentifier = 'customerIdentifier';

$result = $accounts->getAccountsByCustomer($customerIdentifier);

```


[Back to List of Controllers](#list_of_controllers)

## <a name="catalog_controller"></a>![Class: ](https://apidocs.io/img/class.png ".CatalogController") CatalogController

### Get singleton instance

The singleton instance of the ``` CatalogController ``` class can be accessed from the API Client.

```php
$catalog = $client->getCatalog();
```

### <a name="get_catalog"></a>![Method: ](https://apidocs.io/img/method.png ".CatalogController.getCatalog") getCatalog

> Get Catalog


```php
function getCatalog()
```

#### Example Usage

```php

$result = $catalog->getCatalog();

```


[Back to List of Controllers](#list_of_controllers)

## <a name="orders_controller"></a>![Class: ](https://apidocs.io/img/class.png ".OrdersController") OrdersController

### Get singleton instance

The singleton instance of the ``` OrdersController ``` class can be accessed from the API Client.

```php
$orders = $client->getOrders();
```

### <a name="get_order"></a>![Method: ](https://apidocs.io/img/method.png ".OrdersController.getOrder") getOrder

> TODO: Add a method description


```php
function getOrder($referenceOrderID)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| referenceOrderID |  ``` Required ```  | Reference Order ID |



#### Example Usage

```php
$referenceOrderID = 'referenceOrderID';

$result = $orders->getOrder($referenceOrderID);

```


### <a name="create_order"></a>![Method: ](https://apidocs.io/img/method.png ".OrdersController.createOrder") createOrder

> TODO: Add a method description


```php
function createOrder($body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| body |  ``` Required ```  | TODO: Add a parameter description |



#### Example Usage

```php
$body = new CreateOrderRequestModel();

$result = $orders->createOrder($body);

```


### <a name="get_orders"></a>![Method: ](https://apidocs.io/img/method.png ".OrdersController.getOrders") getOrders

> TODO: Add a method description


```php
function getOrders($options)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| accountIdentifier |  ``` Optional ```  | TODO: Add a parameter description |
| customerIdentifier |  ``` Optional ```  | TODO: Add a parameter description |
| externalRefID |  ``` Optional ```  | TODO: Add a parameter description |
| startDate |  ``` Optional ```  | TODO: Add a parameter description |
| endDate |  ``` Optional ```  | TODO: Add a parameter description |
| elementsPerBlock |  ``` Optional ```  | TODO: Add a parameter description |
| page |  ``` Optional ```  | TODO: Add a parameter description |



#### Example Usage

```php
$accountIdentifier = 'accountIdentifier';
$collect['accountIdentifier'] = $accountIdentifier;

$customerIdentifier = 'customerIdentifier';
$collect['customerIdentifier'] = $customerIdentifier;

$externalRefID = 'externalRefID';
$collect['externalRefID'] = $externalRefID;

$startDate = date("D M d, Y G:i");
$collect['startDate'] = $startDate;

$endDate = date("D M d, Y G:i");
$collect['endDate'] = $endDate;

$elementsPerBlock = 137;
$collect['elementsPerBlock'] = $elementsPerBlock;

$page = 137;
$collect['page'] = $page;


$result = $orders->getOrders($collect);

```


### <a name="create_resend_order"></a>![Method: ](https://apidocs.io/img/method.png ".OrdersController.createResendOrder") createResendOrder

> TODO: Add a method description


```php
function createResendOrder($referenceOrderID)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| referenceOrderID |  ``` Required ```  | TODO: Add a parameter description |



#### Example Usage

```php
$referenceOrderID = 'referenceOrderID';

$result = $orders->createResendOrder($referenceOrderID);

```


[Back to List of Controllers](#list_of_controllers)

## <a name="customers_controller"></a>![Class: ](https://apidocs.io/img/class.png ".CustomersController") CustomersController

### Get singleton instance

The singleton instance of the ``` CustomersController ``` class can be accessed from the API Client.

```php
$customers = $client->getCustomers();
```

### <a name="get_customer"></a>![Method: ](https://apidocs.io/img/method.png ".CustomersController.getCustomer") getCustomer

> Get a customer


```php
function getCustomer($customerIdentifier)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| customerIdentifier |  ``` Required ```  | Customer Identifier |



#### Example Usage

```php
$customerIdentifier = 'customerIdentifier';

$result = $customers->getCustomer($customerIdentifier);

```


### <a name="create_customer"></a>![Method: ](https://apidocs.io/img/method.png ".CustomersController.createCustomer") createCustomer

> Create a new customer


```php
function createCustomer($body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| body |  ``` Required ```  | Request Body |



#### Example Usage

```php
$body = new CreateCustomerRequestModel();

$result = $customers->createCustomer($body);

```


### <a name="get_all_customers"></a>![Method: ](https://apidocs.io/img/method.png ".CustomersController.getAllCustomers") getAllCustomers

> Gets all customers under the platform


```php
function getAllCustomers()
```

#### Example Usage

```php

$result = $customers->getAllCustomers();

```


[Back to List of Controllers](#list_of_controllers)

## <a name="exchange_rates_controller"></a>![Class: ](https://apidocs.io/img/class.png ".ExchangeRatesController") ExchangeRatesController

### Get singleton instance

The singleton instance of the ``` ExchangeRatesController ``` class can be accessed from the API Client.

```php
$exchangeRates = $client->getExchangeRates();
```

### <a name="get_exchange_rates"></a>![Method: ](https://apidocs.io/img/method.png ".ExchangeRatesController.getExchangeRates") getExchangeRates

> Retrieve current exchange rates


```php
function getExchangeRates()
```

#### Example Usage

```php

$result = $exchangeRates->getExchangeRates();

```


[Back to List of Controllers](#list_of_controllers)

## <a name="status_controller"></a>![Class: ](https://apidocs.io/img/class.png ".StatusController") StatusController

### Get singleton instance

The singleton instance of the ``` StatusController ``` class can be accessed from the API Client.

```php
$status = $client->getStatus();
```

### <a name="get_system_status"></a>![Method: ](https://apidocs.io/img/method.png ".StatusController.getSystemStatus") getSystemStatus

> *Tags:*  ``` Skips Authentication ``` 

> Retrieve system status


```php
function getSystemStatus()
```

#### Example Usage

```php

$result = $status->getSystemStatus();

```


[Back to List of Controllers](#list_of_controllers)

## <a name="fund_controller"></a>![Class: ](https://apidocs.io/img/class.png ".FundController") FundController

### Get singleton instance

The singleton instance of the ``` FundController ``` class can be accessed from the API Client.

```php
$fund = $client->getFund();
```

### <a name="get_credit_cards"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.getCreditCards") getCreditCards

> List all credit cards registered on this platform


```php
function getCreditCards()
```

#### Example Usage

```php

$result = $fund->getCreditCards();

```


### <a name="create_credit_card"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.createCreditCard") createCreditCard

> Register a new credit card


```php
function createCreditCard($body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| body |  ``` Required ```  | TODO: Add a parameter description |



#### Example Usage

```php
$body = new CreateCreditCardRequestModel();

$result = $fund->createCreditCard($body);

```


### <a name="create_unregister_credit_card"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.createUnregisterCreditCard") createUnregisterCreditCard

> Unregister a credit card


```php
function createUnregisterCreditCard($body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| body |  ``` Required ```  | TODO: Add a parameter description |



#### Example Usage

```php
$body = new UnregisterCreditCardRequestModel();

$result = $fund->createUnregisterCreditCard($body);

```


### <a name="create_deposit"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.createDeposit") createDeposit

> Fund an account


```php
function createDeposit($body)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| body |  ``` Required ```  | TODO: Add a parameter description |



#### Example Usage

```php
$body = new DepositRequestModel();

$result = $fund->createDeposit($body);

```


### <a name="get_deposit"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.getDeposit") getDeposit

> Get details for a specific credit card deposit


```php
function getDeposit($depositId)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| depositId |  ``` Required ```  | Deposit ID |



#### Example Usage

```php
$depositId = 'depositId';

$result = $fund->getDeposit($depositId);

```


### <a name="get_credit_card"></a>![Method: ](https://apidocs.io/img/method.png ".FundController.getCreditCard") getCreditCard

> Get details for a specific credit card


```php
function getCreditCard($token)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| token |  ``` Required ```  | Card Token |



#### Example Usage

```php
$token = 'token';

$result = $fund->getCreditCard($token);

```


[Back to List of Controllers](#list_of_controllers)




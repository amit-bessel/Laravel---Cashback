<?php

require_once "../vendor/autoload.php";

$platformName = 'QAPlatform2'; // RaaS v2 API Platform Name
$platformKey = 'apYPfT6HNONpDRUj3CLGWYt7gvIHONpDRUYPfT6Hj'; // RaaS v2 API Platform Key

$client = new RaasLib\RaasClient($platformName, $platformKey);

 //$accounts = $client->getAccounts();
// $result = $accounts->getAllAccounts();

// $accountIdentifier = 'account-one';

// $result = $accounts->getAccount($accountIdentifier);



$catalog = $client->getCatalog();

$result = $catalog->getCatalog();
 echo "<pre>";
 print_r($result);exit();
?>
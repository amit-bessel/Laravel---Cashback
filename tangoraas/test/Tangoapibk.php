<?php

require_once __DIR__."/vendor/autoload.php";

//class TangoTest {

	function getcatalog(){

		//echo 'amit';exit;

		$platformName = 'checkoutsaver-test'; // RaaS v2 API Platform Name
		$platformKey = 'CYkyKXZh?SEoODWM$Vnjo!HOmtm@$w!DN@aawNClS$Bzb'; // RaaS v2 API Platform Key

		$client = new RaasLib\RaasClient($platformName, $platformKey);

		//$accounts = $client->getAccounts();

		//$result = $accounts->getAllAccounts();

		$catalog = $client->getCatalog();

		$result = $catalog->getCatalog();

		return $result;

		//echo "<pre>";print_r($result);
	}

	//testme();

	

//}

